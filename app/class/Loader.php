<?php

namespace CandleLight;

class Loader{
    private $path;
    private $content;
    public function __construct(string $path){
        $this->path = $path;
        $content = file_get_contents($path);
        $this->content = json_decode($content);
    }
    public function getPath(): string{
        return $this->path;
    }
    public function getBasename(string $suffix = ''): string{
        return basename($this->path, $suffix);
    }
    public function __invoke(){
        return $this->content;
    }
}