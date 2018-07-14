<?php

namespace CandleLight\Validation;

use CandleLight\Validation;

/**
 * Validation fails if the field string is empty, only consists of whitespace or does not exist
 * @package CandleLight\Validation
 */
class NotEmpty extends Validation{

    protected function validate(): bool{
        $field = $this->getModel()->{$this->getField()};
        if (!isset($field) || empty(trim($field))) {
            return true;
        }
        return false;
    }

    public function getMessage(): string{
        return sprintf('%s needs to be set and not be empty or only whitespace.', $this->getField());
    }
}

/* @var \CandleLight\App $app */
$app->addValidation('not-empty', NotEmpty::class);