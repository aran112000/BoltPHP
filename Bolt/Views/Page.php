<?php
namespace Bolt\Views;

use Bolt\Components\Router;

/**
 * Class Page
 * @package Bolt\Views
 */
class Page extends View {

    private $router = null;

    /**
     *
     */
    public function __construct() {
        $this->setTemplate('master');
        $this->setTemplateVariables([
            'PAGE_BODY' => $this->getInnerPageContent(),
        ]);
    }

    /**
     *
     */
    protected function getInnerPageContent() {
        $this->router = new Router();
        $module = $this->router->setRoute();

        return $module->getHtml();
    }

    /**
     * @param array $url_parts
     * @param int   $path_count
     */
    public function controller(array $url_parts, $path_count) {
        // TODO: Implement controller() method.
    }
}