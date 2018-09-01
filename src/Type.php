<?php


namespace CandleLight;

use Slim\App as Slim;

/**
 * General Type provider
 * @package CandleLight
 */
class Type{

    /* @var array */
    private $settings;
    /* @var Model */
    private $model;

    /**
     * Builds up the type from the given settings object
     * @param array $settings content type definition array
     */
    public function __construct(array $settings){
        $this->settings = $settings;
        $this->model = $this->buildModel();
    }

    /**
     * Generates the types model class
     * @return string The class-name
     */
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
                $this->table = $settings['table'];
                $this->connection = $settings['connection'];
            }

            public static function applySettings(array $settings): void{
                self::$cdl_settings = $settings;
            }

        });
        $class::applySettings($this->settings);
        return $class;
    }

    /**
     * Gets an instance of the current type model
     * @return Model
     */
    public function new(): Model{
        return new $this->model;
    }

    /**
     * Gets an intsance of the current type model
     * @return Model
     */
    public function __invoke(): Model{
        return $this->new();
    }

    /**
     * Returns the types settings object
     * @return array
     */
    public function getSettings(): array{
        return $this->settings;
    }

    /**
     * Adds the types routes to the given Slim instance
     * @param App $cdl CDL Application instance
     * @param Slim $app Slim Framework intsance
     */
    public function applyRoutes(App $cdl, Slim $app){
        $routes = System::getRoutesFromSettings($this->settings);
        foreach ($routes as $method => $routes) {
            Dispatcher::run($cdl, $method, $app, $this, $routes);
        }
    }
}