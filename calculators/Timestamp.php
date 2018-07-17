<?php


namespace CandleLight\Validation;

use CandleLight\Calculator;

class Timestamp extends Calculator{
    public function apply(){
        $this->getModel()->{$this->getField()} = time();
    }
}

/* @var \CandleLight\App $app */
$app->addCalculator('timestamp', Timestamp::class);