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
     * @param App $cdl CDL Application instance
     * @param string $method http method for these set of routes
     * @param Slim $app Slim Framework instance
     * @param Type $type Type object, which is mapped to the given routes
     * @param array $routes list of routes to register
     */
    public static function run(App $cdl, string $method, Slim $app, Type $type, array $routes): void{
        foreach ($routes as $options) {
            if (isset($options['action']) && $cdl->hasRoute($method, $options['action'])) {
                $route = $cdl->getRoute($method, $options['action']);
                self::hookRoute($cdl, $app, new $route($cdl, $type, $options), $method);
            } else {
                self::hookError($cdl, $app, $method, $options);
            }
        }
    }

    /**
     * Hooks a custom route into the slim framework
     * @param App $cdl CDL Application instance
     * @param Slim $app CDL Application instance
     * @param Route $route Route instance
     * @param string $method http method string
     */
    private static function hookRoute(App $cdl, Slim $app, Route $route, string $method){
        $options = $route->getOptions();
        $routing = $app->{$method}($options['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($route){
            return $route->dispatch($request, $response, $args);
        }));
        self::applyMiddleware($cdl, $options, $routing);
    }

    /**
     * Adds an error Message, if this route is defined but contains no action
     * @param App $cdl CDL Application instance
     * @param Slim $app Slim Application instance
     * @param string $method http method string
     * @param array $options Route options array
     */
    private static function hookError(App $cdl, Slim $app, string $method, array $options){
        $routing = $app->{$method}($options['url'], System::jsonResponse(function (){
            return Route::noRoute();
        }));
        self::applyMiddleware($cdl, $options, $routing);
    }


    /**
     * Adds all given middleware to the current route if they exist
     * @param App $cdl CDL Application instance
     * @param array $settings Route settings
     * @param \Slim\Route $route Route Object
     */
    private static function applyMiddleware(App $cdl, array $settings, \Slim\Route $route): void{
        if (isset($settings['middleware']) && !empty($settings['middleware'])) {
            foreach ($settings['middleware'] as $middleware) {
                if ($cdl->hasMiddleware($middleware)) {
                    /* @var $middleware Middleware */
                    $middleware = $cdl->getMiddleware($middleware);
                    $route->add($middleware::getInstance());
                }
            }
        }
    }
}