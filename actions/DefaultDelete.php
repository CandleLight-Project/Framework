<?php


namespace CandleLight\Route;


use CandleLight\Model;
use CandleLight\Route;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Default action for the GET Routes
 * @package CandleLight\Route
 */
class DefaultDelete extends Route{

    /**
     * Function to execute, if this route is called
     * @param Request $request HTTP Request object
     * @param Response $response HTTP Response object
     * @param array $args arguments array
     * @return mixed
     * @throws \Exception
     */
    public function dispatch(Request $request, Response $response, array $args){
        $type = $this->getType();
        $route = $this->getOptions();

        /* @var $query Model */
        $query = $type->new();
        foreach ($args as $key => $value) {
            $query = $query->where($key, $route['operator'], $value);
        }
        $query = $query->firstOrFail();
        $query->delete();
        return $query;
    }
}

/* @var \CandleLight\App $app */
$app->addRoute(Route::DELETE, 'default', DefaultDelete::class);