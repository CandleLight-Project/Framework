<?php


namespace CandleLight;

/**
 * Helper, to load and provide multiple json files from disk
 * @package CandleLight
 */
class MultiLoader{
    private $files = [];

    /**
     * Loads all files matching the given pattern
     * @param string $pattern glob pattern
     * @param int $flags glob flag
     */
    public function __construct(string $pattern, int $flags = 0){
        require_once 'Loader.php';

        $files = glob($pattern, $flags);
        foreach ($files as $file) {
            array_push($this->files, new Loader($file));
        }
    }

    /**
     * Returns the list of all loaded files
     * @return Loader[]
     */
    public function __invoke(): array{
        return $this->files;
    }
}