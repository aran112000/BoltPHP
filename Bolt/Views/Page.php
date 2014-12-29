<?php
namespace Bolt\Views;

/**
 * Class Page
 * @package Bolt\Views
 */
class Page extends View {

    /**
     *
     */
    public function __construct() {
        $this->setTemplate('master');
    }
}