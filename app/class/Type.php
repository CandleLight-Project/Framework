<?php


namespace CandleLight;

use Slim\App as Slim;


class Type{
    private $settings;
    private $model;

    public function __construct($settings){
        $this->settings = $settings;
        $this->model = $this->buildModel();
    }

    private function buildModel(): string{
        $class = get_class(new class extends Model{

            private static $cdl_settings;

            public function __construct(array $attributes = []){
                parent::__construct($attributes);
                if (self::$cdl_settings) {
                    $this->setSettings();
                }
            }

            private function setSettings(): void{
                $settings = self::$cdl_settings;
                $this->table = $settings->table;
                $this->connection = $settings->connection;
            }

            public static function applySettings(\stdClass $settings): void{
                self::$cdl_settings = $settings;
            }

        });
        $class::applySettings($this->settings);
        return $class;
    }

    public function new(): Model{
        return new $this->model;
    }

    public function __invoke(): Model{
        return $this->new();
    }

    public function getSettings(): \stdClass{
        return $this->settings;
    }

    public function applyRoutes(Slim $app){
        $routes = System::getRoutesFromSettings($this->settings);
        foreach ($routes as $method => $routes) {
            Dispatcher::run($method, $app, $this, $routes);
        }
    }
}