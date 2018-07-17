<?php


namespace CandleLight;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Generic Model for dynamic types
 * @package CandleLight
 */
abstract class Model extends Eloquent{

    private $validations = [];

    public function applyCalculators(App $cdl, \stdClass $settings): void{
        foreach ($settings->fields as $field) {
            if (!isset($field->calculations)) {
                continue;
            }
            foreach ($field->calculations as $calculation) {
                if (!is_object($calculation)) {
                    $calculation = [
                        'name' => $calculation,
                        'values' => []
                    ];
                } else {
                    $calculation = (array)$calculation;
                }
                if ($cdl->hasCalculator($calculation['name'])) {
                    $calcObject = $cdl->getCalculator($calculation['name']);
                    /* @var $calc Calculator */
                    $calc = new $calcObject($this, $field->name, $calculation['values']);
                    $calc->apply();
                }
            }
        }
    }

    /**
     * Executes all validations for this model
     * @param App $cdl CDL application instance
     * @param \stdClass $settings model settings
     * @return bool true on error
     */
    public function doValidation(App $cdl, \stdClass $settings): bool{
        foreach ($settings->fields as $field) {
            // Skip fields without validation
            if (!isset($field->validation)) {
                continue;
            }
            foreach ($field->validation as $validation) {
                if (!is_object($validation)) {
                    $validation = [
                        'name' => $validation,
                        'values' => []
                    ];
                } else {
                    $validation = (array)$validation;
                }
                if ($cdl->hasValidation($validation['name'])) {
                    $validationObject = $cdl->getValidation($validation['name']);
                    array_push($this->validations, new $validationObject($this, $field->name, $validation['values']));
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


}