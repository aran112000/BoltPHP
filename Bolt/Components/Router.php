<?php
namespace Bolt\Components;

/**
 * Class Router - Used for URL routing to your modules
 * @package Bolt\Compontents
 */
class Router {

    protected $url_controller_mappings = [
        '*' => 'Pages/_Default',
        '/' => 'Pages/Home',
    ];

    protected $uri = null;
    protected $url_parts = null;
    protected $url_part_count = -1;

    /**
     *
     */
    public function setRoute() {
        $controller = $this->getUrlMapping();

        $fully_qualified_class_name = '\Bolt\Modules\\' . $controller;

        $module = new $fully_qualified_class_name();
        $module->__controller($this->url_parts, $this->url_part_count);

        return $module;
    }

    /**
     *
     */
    private function setupEnvironment() {
        $this->uri = URI;
        $this->url_parts = explode('/', $this->uri);
        array_shift($this->url_parts); // Remove the first (blank) path element
        $this->url_part_count = count($this->url_parts);
    }

    /**
     * @return string
     */
    protected function getUrlMapping() {
        $controller = null;
        $this->setupEnvironment();

        // Check for a complete URL match
        if (isset($this->url_controller_mappings[$this->uri])) {
            $controller = $this->url_controller_mappings[$this->uri];
        } else {
            // Check for the most accurate URL match working our way down to the most basic URI match
            // If no match is found, then the _Default controller will be called instead
            for ($i = 0; $i < $this->url_part_count; $i++) {
                if (isset($this->url_controller_mappings[$i])) {
                    $controller = $this->url_controller_mappings[$i];
                    break;
                }
            }
        }

        if ($controller === null) {
            $controller = $this->url_controller_mappings['*'];
        }

        return str_replace(['/', '.php'], ['\\', ''], $controller);
    }
}