<?php


namespace CandleLight;

/**
 * General Validation Template
 * @package CandleLight
 */
abstract class Validation{
    private $model;
    private $field;
    private $values;
    private $status;

    const ERROR = 1;
    const SUCCESS = 2;

    /**
     * Generates the validation object for one field in the given model
     * @param Model $model
     * @param string $field
     * @param array $values
     */
    public function __construct(Model $model, string $field, array $values = []){
        $this->model = $model;
        $this->field = $field;
        $this->values = $values;

        $this->status = self::SUCCESS;
    }

    /**
     * Returns the field name, which this validator is validating
     * @return string
     */
    public function getField(): string{
        return $this->field;
    }

    /**
     * Returns the model, this validator is attached to
     * @return Model
     */
    public function getModel(): Model{
        return $this->model;
    }

    /**
     * Returns the given validation values provided by the type.json
     * @return array
     */
    public function getValues(): array{
        return $this->values;
    }

    /**
     * Returns the validation status
     * @return int
     */
    public function getStatus(): int{
        return $this->status;
    }

    /**
     * Kicks of the validation and sets the status accordingly
     * @return bool
     */
    public function doValidation(): bool{
        $res = $this->validate();
        if ($res) {
            $this->status = self::ERROR;
        }
        return $res;
    }

    /**
     * User defined validation process
     * @return bool true on error
     * @abstract
     */
    abstract protected function validate(): bool;

    /**
     * User defined validation message
     * @return string
     * @abstract
     */
    abstract public function getMessage(): string;
}