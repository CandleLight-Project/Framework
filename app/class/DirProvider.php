<?php


namespace CandleLight;

/**
 * Includes files from the disk and adds scoped data to them
 * @package CandleLight
 */
abstract class DirProvider{

    /**
     * Use glob to include multiple files
     * @param string $pattern glob pattern
     * @param int $flag glob flag
     * @param array $data available data in the included files scope
     */
    public static function glob(string $pattern, int $flag = 0, array $data = []){
        $paths = glob($pattern, $flag);
        extract($data);
        foreach ($paths as $path){
            require($path);
        }
    }
}