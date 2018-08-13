<?php


namespace CandleLight;

use Slim\App as Slim;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Error helper, which can be returned as a json response
 * @package CandleLight
 */
class Error{
    public $message;
    public $error;

    /**
     * Builds the error message
     * @param mixed $message The error message, object or array
     */
    public function __construct($message){
        $this->message = $message;
        $this->error = true;
    }

    /**
     * Injects a custom error handling into the Slim-framework
     * @param Slim $app Slim Framework instance
     */
    public static function registerSystemErrors(Slim $app): void{
        /** @var Container $c */
        $c = $app->getContainer();
        // Exception Handler
        $c['errorHandler'] = function ($c){
            /**
             * Custom exception cather for the slim-framework
             * @param Request $request Request Object
             * @param Response $response Response Object
             * @param \Exception $exception Thrown Exception
             * @return Response Object
             */
            return function (Request $request, Response $response, \Exception $exception) use ($c): Response{
                /** @var Response $response */
                $response = $c['response'];
                return $response
                    ->withStatus(500)
                    ->withJson(new self($exception->getMessage()));
            };
        };
        // 404 Handler
        $c['notFoundHandler'] = function ($c){
            /**
             * Custom 404 handler for the slim-framework
             * @param Request $request Request Object
             * @param Response $response Response Object
             * @return Response Object
             */
            return function (Request $request, Response $response) use ($c){
                return $c['response']
                    ->withStatus(404)
                    ->withJson(new self('Route not defined'));
            };
        };
        // Method not supported Handler
        $c['notAllowedHandler'] = function ($c){
            return function ($request, $response, $methods) use ($c){
                return $c['response']
                    ->withStatus(405)
                    ->withJson(new self('Method needs to be ' . implode($methods, ', ') . ' for this route.'));
            };
        };
    }
}