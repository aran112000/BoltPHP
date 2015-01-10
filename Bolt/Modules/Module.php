<?php
namespace Bolt\Modules;

/**
 * Class Module
 * @package Bolt
 */
abstract class Module {

    /**
     * @param array $url_parts
     * @param int   $path_count
     */
    public abstract function controller(array $url_parts, $path_count);
}