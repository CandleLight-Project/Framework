<?php

namespace CandleLight\Validation;

use CandleLight\Validation;

/**
 * Validation fails if the field string length is not in the given range
 * @package CandleLight\Validation
 */
class StringRange extends Validation{

    protected function validate(): bool{
        $field = $this->getModel()->{$this->getField()};
        $values = $this->getValues();
        if (strlen($field) < $values[0]){
            return true;
        }
        if (isset($values[1]) && strlen($field) > $values[1]){
            return true;
        }
        return false;
    }

    public function getMessage(): string{
        $values = $this->getValues();
        return sprintf('%s needs to be between %d and %d digits long.', $this->getField(), $values[0], $values[1]);
    }
}

/* @var \CandleLight\App $app */
$app->addValidation('range', StringRange::class);