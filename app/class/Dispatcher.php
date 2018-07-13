<?php


namespace CandleLight;

use \Slim\App as Slim;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;


abstract class Dispatcher{
    public static function run(string $method, Slim $app, Type $type, array $routes){
        switch ($method) {
            case 'delete':
                break;
            case 'post':
                self::Post($app, $type, $routes);
                break;
            case 'put':
                break;
            default:
                self::Get($app, $type, $routes);
                break;
        }
    }

    private static function Get(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->get($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $type = $type->new();
                foreach ($args as $key => $value) {
                    $type = $type->where($key, $route['operator'], $value);
                }
                return $type->get()->toArray();
            }));
        }
    }

    private static function Post(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->post($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $type = $type->new();
                foreach ($request->getParams() as $key => $value){
                    $type->{$key} = $value;
                }
                if ($type->doValidation()){
                    return new Error($type->getValidationMessage());
                }
                $type->save();
                return $type;
            }));
        }
    }
}