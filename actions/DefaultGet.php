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
class DefaultGet extends Route{

    /**
     * Function to execute, if this route is called
     * @param Request $request HTTP Request object
     * @param Response $response HTTP Response object
     * @param array $args arguments array
     * @return mixed
     */
    public function dispatch(Request $request, Response $response, array $args){
        $type = $this->getType();
        $route = $this->getOptions();

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
    }
}

/* @var \CandleLight\App $app */
$app->addRoute(Route::GET, 'default', DefaultGet::class);