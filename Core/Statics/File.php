<?php

namespace Core\Statics;

/**
 * Class File
 * @package Core\Statics
 */
class File {

    /**
     * @param $file
     *
     * @return string
     */
    public static function getExtension($file) {
        $farr = explode('.', $file);
        $ext = strlen($farr[count($farr) - 1]);

        return $f = strtolower(substr($file, -$ext));
    }
}