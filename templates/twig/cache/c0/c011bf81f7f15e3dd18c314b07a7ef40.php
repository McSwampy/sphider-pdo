<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Sandbox\SecurityNotAllowedTestError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* admin-error.html */
class __TwigTemplate_4ff8b16d13b9fa92c34568650b539fd6 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<html>
    <head>
        <style>
            * {
                padding: 0;
                margin: 0;
            }
            body {
                background-color: #333333;
                color: #c3c3c3;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: system-ui;
            }
            body div {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            body div h1, body div h2 {
                color: #ffc000;
            }
            body div h1 {
                font-size: xxx-large;
                margin-bottom: 20pt;
            }
            body div h2 {
                font-size: x-large;
                margin-bottom: 10pt;
            }
            body div p {
                padding: 5pt;
                font-size: small;
                text-shadow: 1pt 1pt black, -1pt -1pt black, 0pt 0pt 5pt black;
            }
        </style>
        <title>Internal Error</title>
    </head>
    <body>
        <div>
        <h1>Internal error</h1>
        ";
        // line 43
        if ((($context["error_source_name"] ?? null) != "")) {
            // line 44
            yield "            <h2>";
            yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["error_source_name"] ?? null), "html", null, true);
            yield "</h2>
        ";
        }
        // line 46
        yield "        ";
        if ((($context["exception"] ?? null) != "")) {
            // line 47
            yield "            <p>";
            yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["exception"] ?? null), "getMessage", [], "method", false, false, false, 47), "html", null, true);
            yield "</p>
            <p>File: ";
            // line 48
            yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["exception"] ?? null), "getFile", [], "method", false, false, false, 48), "html", null, true);
            yield "</p>
            <p>Line: ";
            // line 49
            yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["exception"] ?? null), "getLine", [], "method", false, false, false, 49), "html", null, true);
            yield "</p>
        ";
        }
        // line 51
        yield "        </div>
    </body>
</html>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "admin-error.html";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  112 => 51,  107 => 49,  103 => 48,  98 => 47,  95 => 46,  89 => 44,  87 => 43,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "admin-error.html", "/home/vincent/git/sphider-pdo/templates/twig/admin-error.html");
    }
}
