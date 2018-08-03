<?php

namespace CandleLight;

/**
 * General Filter Template
 * @package CandleLight
 */
abstract class Filter extends PlugIn{
    abstract public function apply(): void;
}