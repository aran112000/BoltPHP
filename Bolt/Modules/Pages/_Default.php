<?php
namespace Bolt\Modules\Pages;

use Bolt\Views\View;

/**
 * Class Default
 * @package Bolt\Modules\Pages
 */
class _Default extends View {

    /**
     * @param array $url_parts
     * @param int   $path_count
     */
    public function __controller(array $url_parts, $path_count) {
        parent::__controller($url_parts, $path_count);

        // Example page title ONLY - TODO - Pull page data from the Database and create a fallback 404 view to be used if there's no Database match
        $page_title = ucwords(urldecode(end($url_parts)));
        $this->setTemplateVariables(['page' => ['title' => $page_title]]);
    }
}