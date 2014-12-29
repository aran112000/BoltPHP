<?php

/* footer.tpl */
class __TwigTemplate_87798f965159e437cc554ccbb1c5dd6f4fdd49c9f2bf5c47d084c03590a8426d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('footer', $context, $blocks);
    }

    public function block_footer($context, array $blocks = array())
    {
        // line 2
        echo "    <footer>
        <small>&copy; ";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["company_name"]) ? $context["company_name"] : null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo ". All Rights Reserved.</small>
    </footer>
";
    }

    public function getTemplateName()
    {
        return "footer.tpl";
    }

    public function getDebugInfo()
    {
        return array (  29 => 3,  26 => 2,  20 => 1,);
    }
}
