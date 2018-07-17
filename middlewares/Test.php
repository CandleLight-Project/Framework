<?php

namespace CandleLight\Middleware;

use CandleLight\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Test extends Middleware{

    function apply(Request $request, Response $response, callable $next): Response{
        $response = $next($request, $response);
        $data = json_decode($response->getBody()->__toString());
        $data->timestamp = time();
        $response = $response->withJson($data);
        return $response;
    }
}

/* @var \CandleLight\App $app */
$app->addMiddleware('test', Test::class);