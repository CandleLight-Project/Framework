<?php


namespace CandleLight;


class MultiLoader{
    private $files = [];
    public function __construct(string $pattern, int $flags = 0){
        require_once 'Loader.php';

        $files = glob($pattern, $flags);
        foreach ($files as $file){
            array_push($this->files, new Loader($file));
        }
    }
    public function __invoke(){
        return $this->files;
    }
}