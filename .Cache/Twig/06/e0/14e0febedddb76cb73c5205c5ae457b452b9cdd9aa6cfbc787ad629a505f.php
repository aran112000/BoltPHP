<?php

/* header.tpl */
class __TwigTemplate_06e014e0febedddb76cb73c5205c5ae457b452b9cdd9aa6cfbc787ad629a505f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'header' => array($this, 'block_header'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('header', $context, $blocks);
    }

    public function block_header($context, array $blocks = array())
    {
        // line 2
        echo "    <header>
        <img src=\"/Themes/Default/images/logo.png\" alt=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["company_name"]) ? $context["company_name"] : null), "html", null, true);
        echo "\" width=\"150\" />
    </header>
";
    }

    public function getTemplateName()
    {
        return "header.tpl";
    }

    public function getDebugInfo()
    {
        return array (  29 => 3,  26 => 2,  20 => 1,);
    }
}
