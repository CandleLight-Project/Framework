<?php


namespace CandleLight;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * General Middleware Template
 * @package CandleLight
 */
abstract class Middleware{

    /**
     * Method to pass to the slim framework
     * @param Request $request Request Object
     * @param Response $response Response Object
     * @param callable $next calls the next middleware
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next): Response{
        return $this->apply($request, $response, $next);
    }

    public static function getInstance(){
        $middleware = static::class;
        return new $middleware;
    }

    /**
     * Applies the Middleware to the current Request
     * @param Request $request Request Object
     * @param Response $response Response Object
     * @param callable $next calls the next middleware
     * @return Response
     */
    abstract function apply(Request $request, Response $response, callable $next): Response;
}