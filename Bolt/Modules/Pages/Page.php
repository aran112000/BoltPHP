<?php
namespace Bolt\Modules\Pages;

use Bolt\Views\View;

/**
 * Class Page
 * @package Bolt\Modules\Pages
 */
class Page extends View {

    /**
     * @param array $url_parts
     * @param int   $path_count
     */
    public function controller(array $url_parts, $path_count) {
        parent::controller($url_parts, $path_count);

        // Example page title ONLY - TODO - Pull page data from the Database and create a fallback 404 view to be used if there's no Database match
        $page_title = ucwords(urldecode(end($url_parts)));
        $this->setTemplateVariables(['page' => ['title' => $page_title]]);
    }
}