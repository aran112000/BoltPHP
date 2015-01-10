<?php

namespace Bolt\Views;

use Bolt\Modules\Module, Bolt\Statics\Setting;

/**
 * Class View
 * @package Core\Views
 */
class View extends Module {

    /**
     * @var null|\Twig_Environment
     */
    private static $twig = null;
    /**
     * @var null
     */
    private static $twig_template_directories = null;
    /**
     * @var null|array
     */
    private static $global_template_variables = null;

    /**
     * @var null|string
     */
    private $template = null;

    /**
     * @var array
     */
    private $template_variables = [];

    /**
     *
     */
    public function __construct() {
        $this->setDefaultTemplate();
    }

    /**
     * @param array $url_parts
     * @param int   $path_count
     */
    public function controller(array $url_parts, $path_count) {

    }

    /**
     * Try to automate the setting of a template name based on the calling class name converted to lowercase
     */
    private function setDefaultTemplate() {
        $called_class_parts = str_replace('_', '', explode('\\', strtolower(get_called_class())));
        $this->setTemplate(end($called_class_parts));
    }

    /**
     * @param string $template_name
     */
    protected function setTemplate($template_name) {
        $this->template = str_replace('.tpl', '', $template_name) . '.tpl';
    }

    /**
     * @param array $template_variables
     */
    protected function setTemplateVariables(array $template_variables) {
        $this->template_variables = array_merge($this->template_variables, $template_variables);
    }

    /**
     * @param array       $variables
     * @param string|null $template_name
     *
     * @return string
     */
    public function getHtml(array $variables = [], $template_name = null) {
        if ($variables !== []) {
            $this->setTemplateVariables($variables);
        }
        if ($template_name !== null) {
            $this->setTemplate($template_name);
        }

        return $this->doRenderTemplate();
    }

    /**
     * @return array
     */
    protected function getTemplateVariables() {
        if (self::$global_template_variables === null) {
            $this->setGlobalTemplateVars();
        }

        return array_merge(self::$global_template_variables, $this->template_variables);
    }

    /**
     *
     */
    private function doRenderTemplate() {
        $twig_environment = $this->getTwigEnvironment();

        return $twig_environment->render($this->template, $this->getTemplateVariables());
    }

    /**
     *
     */
    private function setGlobalTemplateVars() {
        if (self::$global_template_variables === null) {
            self::$global_template_variables = [
                'site_name' => Setting::get('site_name'),
                'page' => [
                    'seo' => [
                        'title_tag' => 'TODO',
                        'meta_description' => 'TODO',
                        'meta_keywords' => 'TODO',
                    ],
                ],
            ];
        }
    }

    /**
     * @return \Twig_Environment
     */
    private function getTwigEnvironment() {
        if (self::$twig === null) {
            $this->setTwigEnvironment();
        }

        return self::$twig;
    }

    protected function setTwigEnvironment() {
        require(ROOT . DS . 'Bolt' . DS . 'Libs' . DS . 'Twig' . DS . 'Autoloader.php');
        \Twig_Autoloader::register();
        $this->setTwigTemplateDirectories();
        $loader = new \Twig_Loader_Filesystem(self::$twig_template_directories);
        self::$twig = new \Twig_Environment($loader, [
            'cache' => ROOT . DS . 'Data' . DS . 'Cache' . DS . 'Twig',
            'auto_reload' => true,
            'debug' => false,
            'autoescape' => false,
        ]);
    }

    /**
     *
     */
    private function setTwigTemplateDirectories() {
        if (self::$twig_template_directories === null) {
            foreach (glob(ROOT . DS . 'Themes' . DS . '*' . DS . 'Templates' . DS . '*' . DS . '*.tpl') as $template_file_path) {
                $dirs[] = dirname($template_file_path);
            }

            if (!empty($dirs)) {
                self::$twig_template_directories = array_unique($dirs);
            }
        }
    }
}
