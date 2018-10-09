<?php


namespace CandleLight;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Generic Model for dynamic types
 * @package CandleLight
 */
class Model extends Eloquent{

    private $validations = [];

    /**
     * Helper to build plugin data
     * @param mixed $key key or name
     * @param mixed $data data or name
     * @return array
     */
    private function buildPluginData($key, $data){
        if (is_array($data)) {
            $name = $key;
            $attributes = $data;
        } else {
            $name = $data;
            $attributes = [];
        }
        return [
            'name' => $name,
            'attributes' => $attributes
        ];
    }

    /**
     * Applies the filters to the fields
     * @param App $cdl CDL Application instance
     * @param array $settings field settings
     */
    public function applyFilters(App $cdl, array $settings): void{
        foreach ($settings['fields'] as $field) {
            if (!isset($field['filters'])) {
                continue;
            }
            foreach ($field['filters'] as $key => $filter) {
                $data = $this->buildPluginData($key, $filter);
                if ($cdl->hasFilter($data['name'])) {
                    $filterObject = $cdl->getFilter($data['name']);
                    /* @var $filt Filter */
                    $filt = new $filterObject($this, $field['name'], $data['attributes']);
                    $filt->apply();
                }
            }
        }
    }

    /**
     * Applies the calculators to the fields
     * @param App $cdl CDL Application instance
     * @param array $settings field settings
     */
    public function applyCalculators(App $cdl, array $settings): void{
        foreach ($settings['fields'] as $field) {
            if (!isset($field['calculations'])) {
                continue;
            }
            foreach ($field['calculations'] as $key => $calculation) {
                $data = $this->buildPluginData($key, $calculation);
                if ($cdl->hasCalculator($data['name'])) {
                    $calcObject = $cdl->getCalculator($data['name']);
                    /* @var $calc Calculator */
                    $calc = new $calcObject($this, $field['name'], $data['attributes']);
                    $calc->apply();
                }
            }
        }
    }

    /**
     * Executes all validations for this model
     * @param App $cdl CDL application instance
     * @param array $settings model settings
     * @return bool true on error
     */
    public function doValidation(App $cdl, array $settings): bool{
        foreach ($settings['fields'] as $field) {
            if (!isset($field['validation'])) {
                continue;
            }
            foreach ($field['validation'] as $key => $validation) {
                $data = $this->buildPluginData($key, $validation);
                if ($cdl->hasValidation($data['name'])) {
                    $validationObject = $cdl->getValidation($data['name']);
                    array_push($this->validations, new $validationObject($this, $field['name'], $data['attributes']));
                }
            }
        }
        $error = false;
        foreach ($this->validations as $validation) {
            /* @var Validation $validation */
            $error = $validation->doValidation() || $error;
        }
        return $error;
    }

    /**
     * Returns a list of validation messages for every field, which failed its validation
     * @return array
     */
    public function getValidationMessage(): array{
        $fields = [];
        foreach ($this->validations as $validation) {
            /* @var Validation $validation */
            if ($validation->getStatus() == Validation::ERROR) {
                if (!isset($fields[$validation->getField()])) {
                    $fields[$validation->getField()] = [];
                }
                var_dump($validation->getMessage());
                array_push($fields[$validation->getField()], $validation->getMessage());
            }
        }
        return $fields;
    }

    /**
     * Propagates the table settings to the children
     * @param array $attributes
     * @param bool $exists
     * @return Eloquent
     */
    public function newInstance($attributes = [], $exists = false): Model{
        /** @var Model $model */
        $model = parent::newInstance($attributes, $exists);
        $model->setTable($this->getTable());
        return $model;
    }
}