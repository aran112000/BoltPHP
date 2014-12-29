<?php

/* header.tpl */
class __TwigTemplate_6d94e92d61fad9e1c2eb2617494901bd78cb7d86582fc4d26e177436fa313694 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<header>
    <img src=\"/Themes/Default/images/logo.png\" width=\"200\" alt=\"";
        // line 2
        echo (isset($context["site_name"]) ? $context["site_name"] : null);
        echo "\" />
</header>";
    }

    public function getTemplateName()
    {
        return "header.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  22 => 2,  19 => 1,);
    }
}
