<?php

namespace CandleLight;

/**
 * General Filter Template
 * @package CandleLight
 */
abstract class Filter extends PlugIn{

    /**
     * Applies the filter to the current field
     */
    abstract public function apply(): void;

}