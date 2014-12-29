<?php

/* master.tpl */
class __TwigTemplate_8194695733c848ef4c8d44b41d05d8fe1c82afd2ab7259352955e2c8f467d9ab extends Twig_Template
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
        echo "<!DOCTYPE html>
<html dir=\"ltr\" lang=\"en-gb\" tabindex=\"-1\">
<head>
    <meta charset=\"utf-8\"/>
    <title>";
        // line 5
        echo $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "seo", array()), "title_tag", array());
        echo "</title>
    <meta name=\"description\" content=\"";
        // line 6
        echo $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "seo", array()), "meta_description", array());
        echo "\"/>
    <meta name=\"robots\" content=\"index, follow\"/>
    <link rel=\"shortcut icon\" href=\"/favicon.ico\"/>
    <style type=\"text/css\">
        body {
            padding: 0;
            margin: 0;
            font: normal normal normal 16px/22px Arial;
        }
        main, header, footer {
            width: 980px;
            display: block;
            margin: 0 auto;
            overflow: hidden;
            background-color: #f1f1f1;
            padding: 20px;
        }
        main {

        }
        small {
            font-size: 11px;
        }
    </style>
</head>
<body class=\"pages_module pages\">
    ";
        // line 32
        $this->env->loadTemplate("header.tpl")->display($context);
        // line 33
        echo "    <main>
        ";
        // line 34
        echo (isset($context["PAGE_BODY"]) ? $context["PAGE_BODY"] : null);
        echo "
    </main>
    ";
        // line 36
        $this->env->loadTemplate("footer.tpl")->display($context);
        // line 37
        echo "</body>";
    }

    public function getTemplateName()
    {
        return "master.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 37,  68 => 36,  63 => 34,  60 => 33,  58 => 32,  29 => 6,  25 => 5,  19 => 1,);
    }
}
