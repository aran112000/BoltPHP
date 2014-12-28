<!DOCTYPE html>
<html dir="ltr" lang="en-gb" tabindex="-1">
<head>
    <meta charset="utf-8"/>
    <title>{{ page.seo.title_tag }}</title>
    <meta name="description" content="{{ page.seo.meta_description }}"/>
    <meta name="robots" content="index, follow"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <style type="text/css">
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
<body class="pages_module pages">
    {% include "header.tpl" %}
    <main>
        {% block main %}
            Welcome to our website...
        {% endblock %}
    </main>
    {% include "footer.tpl" %}
</body>