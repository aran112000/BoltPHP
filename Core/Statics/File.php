<?php

namespace Core\Statics;

/**
 * Class file
 *
 * @package Core
 */
class file {

    /**
     * @param $file
     *
     * @return string
     */
    public static function get_extension($file) {
        $farr = explode('.', $file);
        $ext = strlen($farr[count($farr) - 1]);

        return $f = strtolower(substr($file, -$ext));
    }
}