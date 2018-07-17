<?php


namespace CandleLight;

/**
 * General Plugin Template
 * @package CandleLight
 */
abstract class PlugIn{

    private $model;
    private $field;
    private $values;

    /**
     * Generates the plugin object for one field in the given model
     * @param Model $model
     * @param string $field
     * @param array $values
     */
    public function __construct(Model $model, string $field, array $values = []){
        $this->model = $model;
        $this->field = $field;
        $this->values = $values;
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
    public function getValues(): array{
        return $this->values;
    }
}