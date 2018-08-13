<?php

namespace CandleLight;

/**
 * General Calculator Template
 * @package CandleLight
 */
abstract class Calculator extends PlugIn{

    /**
     * Checks if the given field changed on the current model
     * @param string $field fieldname
     * @return bool true if the field has changed
     */
    public function fieldChanged(string $field): bool{
        $old = $this->getModel()->getOriginal($field);
        $current = $this->getModel()->{$field};
        return $old != $current;
    }

    abstract public function apply();
}