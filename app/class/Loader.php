<?php

namespace CandleLight;

/**
 * Helper, to load and provide json files from disk
 * @package CandleLight
 */
class Loader{
    private $path;
    private $content;

    /**
     * Reads and provides the given json file
     * @param string $path absolute path to the json file
     */
    public function __construct(string $path){
        $this->path = $path;
        $content = file_get_contents($path);
        $this->content = json_decode($content);
    }

    /**
     * Returns the full path to the file
     * @return string
     */
    public function getPath(): string{
        return $this->path;
    }

    /**
     * Returns the file basename
     * @param string $suffix suffix to cut from the basename
     * @return string
     */
    public function getBasename(string $suffix = ''): string{
        return basename($this->path, $suffix);
    }

    /**
     * Returns the file content
     * @return mixed
     */
    public function __invoke(){
        return $this->content;
    }
}