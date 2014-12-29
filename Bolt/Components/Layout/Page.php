<?php
namespace Bolt\Components\Layout;

/**
 * This class is used as a bootstrap to pull together an whole webpage's content
 *
 * Class Page
 * @package Bolt\Components\Layout
 */
class Page {

    public static $global_page_vars = [
        'company_name' => 'Test Website',
        'page' => [
            'seo' => [
                'title_tag' => 'Welcome to our test page',
                'meta_description' => 'This is an example meta description',
            ],
        ],
    ];

    public static $page_elements = [
        'header',
        'footer',
    ];

    public function setNavigatonLinks() {

    }

    public function __construct() {
        $this->showPage(); // TODO - This will need to be migrated elsewhere once working
    }

    protected function showPage() {
        new View();
    }
}