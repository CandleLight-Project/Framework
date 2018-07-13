<?php


namespace CandleLight;


abstract class Route{
    private $path;
    private $method;

    public function __construct(string $method, string $path){
        $this->method = $method;
        $this->path = $path;
    }

    public function getPath(): string{
        return $this->path;
    }

    public function getMethod(): string{
        return $this->getMethod();
    }

    abstract function apply();
}