<?php


namespace CandleLight;


abstract class Route{
    public function __construct(string $path){
    }
    abstract function apply();
}