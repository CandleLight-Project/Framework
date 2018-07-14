<?php


namespace CandleLight;

use Illuminate\Database\Eloquent\Model;
use Slim\App as Slim;


class Type{
    private $settings;
    public function __construct($settings){
        $this->settings = $settings;
    }

    public function new(): Model{
        return new class extends Model{
            public function doValidation(){
                return false;
            }
            public function getValidationMessage(){
                return '';
            }
        };
    }

    public function applyTypeData(Model $model): Model{
        $model->setTable($this->settings->table);
        $model->setConnection($this->settings->connection);
        return $model;
    }

    public function __invoke(): Model{
        return $this->new();
    }

    public function getSettings(): \stdClass{
        return $this->settings;
    }

    public function applyRoutes(Slim $app){
        $routes = System::getRoutesFromSettings($this->settings);
        foreach ($routes as $method => $routes){
            Dispatcher::run($method, $app, $this, $routes);
        }
    }
}