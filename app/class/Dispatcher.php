<?php


namespace CandleLight;

use \Slim\App as Slim;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;


/**
 * Static route dispatcher API
 * registers all defaul routes
 * @package CandleLight
 * @abstract
 */
abstract class Dispatcher{

    /**
     * Dispatches the given data and registers the appropriate route
     * @param string $method http method for these set of routes
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    public static function run(string $method, Slim $app, Type $type, array $routes): void{
        switch ($method) {
            case 'delete':
                self::Delete($app, $type, $routes);
                break;
            case 'post':
                self::Post($app, $type, $routes);
                break;
            case 'put':
                self::Put($app, $type, $routes);
                break;
            default:
                self::Get($app, $type, $routes);
                break;
        }
    }

    /**
     * Registers all default GET-Routes
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Get(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->get($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                if ($route['firstOrFail']) {
                    return $query->firstOrFail()->toArray();
                } else {
                    return $query->get()->toArray();
                }
            }));
        }
    }

    /**
     * Registers all default POST-Routes
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Post(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->post($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $query = $type->new();
                foreach ($request->getParams() as $key => $value) {
                    $query->{$key} = $value;
                }
                if ($query->doValidation()) {
                    return new Error($query->getValidationMessage());
                }
                $query->save();
                return $query;
            }));
        }
    }

    /**
     * Registers all default PUT-Routes
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Put(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->put($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                $query = $query->firstOrFail();
                foreach ($request->getParams() as $key => $value) {
                    $query->{$key} = $value;
                }
                if ($query->doValidation()) {
                    return new Error($query->getValidationMessage());
                }
                $query->update();
                return $query;
            }));
        }
    }

    /**
     * Registers all default DELETE-Routes
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    private static function Delete(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->delete($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                $query = $query->firstOrFail();
                $query->delete();
                return $query;
            }));
        }
    }
}