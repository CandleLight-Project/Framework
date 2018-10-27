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
     * @param string $model Type-Model classname string
     */
    public function __construct(string $model){
        /** @var Model $model */
        $this->settings = ($model)::getTypeSettings();
        $this->model = new $model();
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
        return $this->new($attributes, $exists);
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