<?php


namespace CandleLight;

/**
 * General Plugin Template
 * @package CandleLight
 */
abstract class PlugIn{

    private $model;
    private $field;
    private $attributes;

    /**
     * Generates the plugin object for one field in the given model
     * @param Model $model
     * @param string $field
     * @param array $attributes
     */
    public function __construct(Model $model, string $field, array $attributes = []){
        $this->model = $model;
        $this->field = $field;
        $this->attributes = $attributes;
    }

    /**
     * Returns the field name for this plugin
     * @return string
     */
    public function getField(): string{
        return $this->field;
    }

    /**
     * Returns the model, this plugin is attached to
     * @return Model
     */
    public function getModel(): Model{
        return $this->model;
    }

    /**
     * Returns the given plugin values provided by the type.json
     * @return array
     */
    public function getAttributes(): array{
        return $this->attributes;
    }

    /**
     * Merges the given attributes with the given default values
     * and returns the result
     * @param array $defaults default attribute values
     * @return array
     */
    public function parseAttributes(array $defaults): array{
        return array_replace($defaults, $this->getAttributes());
    }
}