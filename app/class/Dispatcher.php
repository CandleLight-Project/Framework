<?php


namespace CandleLight;

use \Slim\App as Slim;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;


abstract class Dispatcher{
    public static function run(string $method, Slim $app, Type $type, array $routes){
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

    private static function Get(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->get($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                if ($route['firstOrFail']){
                    return $query->firstOrFail()->toArray();
                }
                else{
                    return $query->get()->toArray();
                }
            }));
        }
    }

    private static function Post(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route) {
            $app->post($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $query = $type->new();
                foreach ($request->getParams() as $key => $value){
                    $query->{$key} = $value;
                }
                if ($query->doValidation()){
                    return new Error($query->getValidationMessage());
                }
                $query->save();
                return $query;
            }));
        }
    }

    private static function Put(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route){
            $app->put($route['url'], System::jsonResponse(function (Request $request, Response $response, array $args) use ($type, $route){
                $query = $type->new();
                foreach ($args as $key => $value) {
                    $query = $query->where($key, $route['operator'], $value);
                }
                $query = $query->firstOrFail();
                foreach ($request->getParams() as $key => $value){
                    $query->{$key} = $value;
                }
                if ($query->doValidation()){
                    return new Error($query->getValidationMessage());
                }
                $query->update();
                return $query;
            }));
        }
    }

    private static function Delete(Slim $app, Type $type, array $routes): void{
        foreach ($routes as $route){
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