<?php

/* footer.tpl */
class __TwigTemplate_c54d2c2b546da509c3eba94b829fcf92e769531a69069d61cd987a1c8df2da0e extends Twig_Template
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
        echo "<footer>
    <small>
        &copy; ";
        // line 3
        echo twig_date_format_filter($this->env, "now", "Y");
        echo " ";
        echo (isset($context["site_name"]) ? $context["site_name"] : null);
        echo ". All Rights Reserved.
    </small>
</footer>";
    }

    public function getTemplateName()
    {
        return "footer.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  23 => 3,  19 => 1,);
    }
}
