<?php


namespace CandleLight\Validation;

use CandleLight\Calculator;

class SerializeField extends Calculator{

    public function apply(){
        $values = $this->getValues();
        $field = $values[0];
        if ($field && $this->fieldChanged($field)){
            $this->getModel()->{$this->getField()} = $this->serialize($this->getModel()->{$field});
        }
    }

    private function serialize(string $field): string{
        return md5($field);
    }
}

/* @var \CandleLight\App $app */
$app->addCalculator('serialize-field', SerializeField::class);