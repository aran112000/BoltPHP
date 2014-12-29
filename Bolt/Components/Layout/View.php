<?php
namespace Bolt\Components\Layout;

/**
 * Class Display
 * @package Bolt\Components\Layout
 */
class View
{

    /**
     * @var bool
     */
    private static $initialised = false;

    /**
     * @var null|array
     */
    protected $template_directories = null;

    /**
     * @var null|\Twig_Environment
     */
    private $twig = null;

    /**
     *
     */
    public function __construct()
    {
        if (!static::$initialised) {
            $this->initTwig();
            static::$initialised = true;
        }
        $this->doDisplay();
    }

    /**
     *
     */
    protected function initTwig()
    {
        $twig_include_path = ROOT . DS . 'Bolt' . DS . 'Libs' . DS . 'Twig' . DS . 'Autoloader.php';
        require($twig_include_path);
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem($this->getTemplateDirectories());
        $this->twig = new \Twig_Environment($loader, [
            'cache' => '.Cache/Twig',
            'auto_reload' => true,
        ]);
    }

    /**
     *
     */
    protected function doDisplay() {
       echo $this->twig->render('main.tpl', \Bolt\Components\Layout\page::$global_page_vars);
    }

    /**
     * @return array|null
     */
    private function getTemplateDirectories() {
        if ($this->template_directories !== null) {
            return $this->template_directories;
        }

        $dir_structure = [
            [
                'Themes',
                '*',
                'Templates',
                '*',
                '*.tpl',
            ],
            [
                'Themes',
                '*',
                'Templates',
                '*.tpl',
            ]
        ];
        foreach ($dir_structure as $dir) {
            $glob_rule = ROOT . DS . implode(DS, $dir);
            foreach (glob($glob_rule) as $template_file_path) {
                $dirs[] = dirname($template_file_path);
            }
        }

        if (!empty($dirs)) {
            $this->template_directories = array_unique($dirs);
        }

        return $this->template_directories;
    }
}