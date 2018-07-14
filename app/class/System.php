<?php


namespace CandleLight;


use Slim\App as Slim;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class System{

    /**
     * Builds a clean route array from the given type settings
     * @param \stdClass $settings
     * @return array the clean route array with the full route urls
     */
    public static function getRoutesFromSettings(\stdClass $settings){
        $routes = [];
        foreach ($settings->routing->routes as $method => $urls) {
            if (!isset($routes[$method])) {
                $routes[$method] = [];
            }
            foreach ($urls as $url) {
                if (!is_array($url)){
                    $url = [$url, '=', false];
                }
                array_push($routes[$method], [
                    "url" => $settings->routing->base . $url[0],
                    "operator" => $url[1],
                    "firstOrFail" => $url[2]
                ]);
            }
        }
        return $routes;
    }

    /**
     * Adds the system information routes to the system
     * @param App $app
     * @param Slim $slim
     */
    public static function reflectionRoutes(App $app, Slim $slim){
        $types = $app->getTypes();

        // List all Types with the full route
        $slim->get('/@types', self::jsonResponse(function () use ($types){
            $return = [];
            foreach ($types as $name => $type) {
                $type = $type->getSettings();
                $list = [
                    '@type' => $name,
                    'title' => $type->title,
                    'description' => $type->description
                ];
                array_push($return, $list);
            }
            return $return;
        }));

        // Show the public type configuration
        $slim->get('/@types/{type}', self::jsonResponse(function ($req, $res, $args) use ($types){
            if (!isset($types[$args['type']])) {
                return new Error(sprintf("Type '%s' is not defined.", $args['type']));
            }
            $settings = $types[$args['type']]->getSettings();
            $settings->routes = self::getRoutesFromSettings($settings);
            unset($settings->routing);
            return $settings;
        }));
    }

    /**
     * Wrapper to archive a JSON-Response for the given route
     * @param callable $fun custom function returning a Object or Array
     * @return callable function to implement into the router
     */
    public static function jsonResponse(callable $fun): callable{
        return function (Request $request, Response $response, array $args) use ($fun){
            return $response->withJson($fun($request, $response, $args));
        };
    }
}