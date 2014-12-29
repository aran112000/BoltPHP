<?php

/* _master.tpl */
class __TwigTemplate_ff0a86257b8b9dbe9571a8a100c6a8a0a8e41c72e38595ffe3810332ce696ac7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'main' => array($this, 'block_main'),
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
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "seo", array()), "title_tag", array()), "html", null, true);
        echo "</title>
    <meta name=\"description\" content=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "seo", array()), "meta_description", array()), "html", null, true);
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
        $this->displayBlock('main', $context, $blocks);
        // line 37
        echo "    </main>
    ";
        // line 38
        $this->env->loadTemplate("footer.tpl")->display($context);
        // line 39
        echo "</body>";
    }

    // line 34
    public function block_main($context, array $blocks = array())
    {
        // line 35
        echo "            Welcome to our website...
        ";
    }

    public function getTemplateName()
    {
        return "_master.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 35,  75 => 34,  71 => 39,  69 => 38,  66 => 37,  64 => 34,  61 => 33,  59 => 32,  30 => 6,  26 => 5,  20 => 1,);
    }
}
