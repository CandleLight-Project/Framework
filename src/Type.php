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
     * @return Model instance of the types model
     */
    private function buildModel(): Model{
        $class = new Model();
        $class->setTable($this->settings['table']);
        $class->setConnection($this->settings['connection']);
        return $class;
    }

    /**
     * Gets an instance of the current type model
     * @param array $attributes
     * @param bool $exists
     * @return Model
     */
    public function new(array $attributes = [], bool $exists = false): Model{
        return $this->model->newInstance($attributes, $exists);
    }

    /**
     * Gets an instance of the current type model
     * @param array $attributes
     * @param bool $exists
     * @return Model
     */
    public function __invoke(array $attributes = [], bool $exists = false): Model{
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
        foreach ($routes as $method => $data) {
            Dispatcher::run($cdl, $method, $app, $this, $data);
        }
    }
}