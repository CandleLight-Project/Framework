<?php


namespace CandleLight;

/**
 * General Validation Template
 * @package CandleLight
 */
abstract class Validation extends PlugIn{
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
        parent::__construct($model, $field, $values);
        $this->status = self::SUCCESS;
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