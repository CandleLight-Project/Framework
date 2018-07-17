<?php


namespace CandleLight;

use \Slim\App as Slim;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;
use Slim\Route;


/**
 * Static route dispatcher API
 * registers all defaul routes
 * @package CandleLight
 * @abstract
 */
abstract class Dispatcher{

    /**
     * Dispatches the given data and registers the appropriate route
     * @param App $cdl CDL Application instance
     * @param string $method http method for these set of routes
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    public static function run(App $cdl, string $method, Slim $app, Type $type, array $routes): void{
        switch ($method) {
            case 'delete':
                self::Delete($cdl, $app, $type, $routes);
                break;
            case 'post':
                self::Post($cdl, $app, $type, $routes);
                break;
            case 'put':
                self::Put($cdl, $app, $type, $routes);
                break;
            default:
                self::Get($cdl, $app, $type, $routes);
                break;
        }
    }

    private static function applyMiddleware(App $cdl, array $settings, Route $route): void{
        if (isset($settings['middlewares']) && !empty($settings['middlewares'])){
            foreach ($settings['middlewares'] as $middleware){
                if ($cdl->hasMiddleware($middleware)){
                    $middleware = $cdl->getMiddleware($middleware);
                    $route->add($middleware::getInstance());
                }
            }
        }
    }

    /**
     * Registers all default GET-Routes
     * @param App $cdl CDL Application instance
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Get(App $cdl, Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $routing = $app->get($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($cdl, $type, $route){
                /* @var $query Model */
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                if (isset($route['firstOrFail']) && $route['firstOrFail']) {
                    return $query->firstOrFail()->toArray();
                } else {
                    return $query->get()->toArray();
                }
            }));
            self::applyMiddleware($cdl, $route, $routing);
        }
    }

    /**
     * Registers all default POST-Routes
     * @param App $cdl CDL Application instance
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Post(App $cdl, Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $routing = $app->post($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($cdl, $type, $route){
                /* @var $query Model */
                $query = $type->new();
                foreach ($request->getParams() as $key => $value) {
                    $query->{$key} = $value;
                }
                $query->applyCalculators($cdl, $type->getSettings());
                $query->applyFilters($cdl, $type->getSettings());
                if ($query->doValidation($cdl, $type->getSettings())) {
                    return new Error($query->getValidationMessage());
                }
                $query->save();
                return $query;
            }));
            self::applyMiddleware($cdl, $route, $routing);
        }
    }

    /**
     * Registers all default PUT-Routes
     * @param App $cdl CDL Application instance
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Put(App $cdl, Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $routing = $app->put($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($cdl, $type, $route){
                /* @var $query Model */
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                $query = $query->firstOrFail();
                foreach ($request->getParams() as $key => $value) {
                    $query->{$key} = $value;
                }
                $query->applyCalculators($cdl, $type->getSettings());
                $query->applyFilters($cdl, $type->getSettings());
                if ($query->doValidation($cdl, $type->getSettings())) {
                    return new Error($query->getValidationMessage());
                }
                $query->update();
                return $query;
            }));
            self::applyMiddleware($cdl, $route, $routing);
        }
    }

    /**
     * Registers all default DELETE-Routes
     * @param App $cdl CDL Application instance
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Delete(App $cdl, Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $routing = $app->delete($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($cdl, $type, $route){
                /* @var $query Model */
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                $query = $query->firstOrFail();
                $query->delete();
                return $query;
            }));
            self::applyMiddleware($cdl, $route, $routing);
        }
    }
}