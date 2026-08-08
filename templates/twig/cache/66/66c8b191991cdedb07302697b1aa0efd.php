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

/* search/search.html */
class __TwigTemplate_280d61cb340e69f6b62438d28b1c8761 extends Template
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
        yield "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<HTML>
<HEAD>
    <meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">
    <title>Sphider</title>
    <link type=\"text/css\" rel=\"stylesheet\" href=\"templates/standard/search.css\">
    <style type=\"text/css\">@import url(\"include/js_suggest/SuggestFramework.css\");</style>
    <script type=\"text/javascript\" src=\"include/js_suggest/SuggestFramework.js\"></script>
    <script type=\"text/javascript\">window.onload = initializeSuggestFramework;</script>
</HEAD>

<BODY>
<h1>Sphider</h1>
<center>
<table cellpadding=\"5\" cellspacing=\"1\" class=\"searchBox\">
<tr>
\t<td align=\"center\">
\t<form action=\"search.php\" method=\"post\">
        <input
            type=\"text\" name=\"query\" id=\"query\"
            size=\"40\" value=\"";
        // line 21
        yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["query"] ?? null), "html", null, true);
        yield "\" action=\"include/js_suggest/suggest.php\"
            columns=\"2\" autocomplete=\"off\" delay=\"1500\"
        >
        <input type=\"submit\" value=\"";
        // line 24
        yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["sph_messages"] ?? null), "Search", [], "any", false, false, false, 24), "html", null, true);
        yield "\">

";
        // line 26
        if (((($context["adv"] ?? null) == 1) || (($context["advanced_search"] ?? null) == 1))) {
            // line 27
            yield "\t<table width = \"100%\">
\t<tr>
\t\t<td width=\"40%\"><input type=\"radio\" name=\"type\" value=\"and\" <?php print \$type==\x27and\x27?\x27checked\x27:\x27\x27?>><?php print \$sph_messages[\x27andSearch\x27]?></td>
\t\t<td><input type=\"radio\" name=\"type\" value=\"or\" <?php print \$_REQUEST[\x27type\x27]==\x27or\x27?\x27checked\x27:\x27\x27?>><?php print \$sph_messages[\x27orSearch\x27]?></td></tr>
\t<tr>
\t\t<td><input type=\"radio\" name=\"type\" value=\"phrase\" <?php print \$_REQUEST[\x27type\x27]==\x27phrase\x27?\x27checked\x27:\x27\x27?>><?php print \$sph_messages[\x27phraseSearch\x27]?></td>
\t\t<td>
            ";
            // line 34
            yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["sph_messages"] ?? null), "show", [], "any", false, false, false, 34), "html", null, true);
            yield "
\t\t\t<select name=\x27results\x27>
                <option value=\"10\" ";
            // line 36
            if ((($context["results_per_page"] ?? null) == 10)) {
                yield "selected";
            }
            yield ">10</option>
                <option value=\"10\" ";
            // line 37
            if ((($context["results_per_page"] ?? null) == 10)) {
                yield "selected";
            }
            yield ">20</option>
                <option value=\"10\" ";
            // line 38
            if ((($context["results_per_page"] ?? null) == 10)) {
                yield "selected";
            }
            yield ">50</option>
                <option value=\"10\" ";
            // line 39
            if ((($context["results_per_page"] ?? null) == 10)) {
                yield "selected";
            }
            yield ">100</option>
\t\t\t</select>
            ";
            // line 41
            yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["sph_messages"] ?? null), "resultsPerPage", [], "any", false, false, false, 41), "html", null, true);
            yield "
\t  \t</td>
\t</tr>
\t</table>
";
        }
        // line 46
        yield "
";
        // line 47
        if ((($context["catid"] ?? null) > 0)) {
            // line 48
            yield "    <b>";
            yield (string) $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["sph_messages"] ?? null), "Search", [], "any", false, false, false, 48), "html", null, true);
            yield "</b>
";
        }
        // line 50
        yield "<?php if (isset(\$catid) && is_numeric(\$catid)){?>
\t<center><b><?php print \$sph_messages[\x27Search\x27]?></b>: <input type=\"radio\" name=\"category\" value=\"<?php print \$catid?>\"><?php print \$sph_messages[\x27Only in category\x27]?> \"<?php print \$tpl_[\x27category\x27][0][\x27category\x27]?>\x27\" <input type=\"radio\" name=\"category\" value=\"-1\" checked><?php print \$sph_messages[\x27All sites\x27]?></center>
<?php  }?>


\t<input type=\"hidden\" name=\"search\" value=\"1\">
\t</form>
\t\t<?php if (\$has_categories && \$search==1 && \$show_categories){?>
\t\t<a href=\"search.php\"><?php print \$sph_messages[\x27Categories\x27]?></a>
\t\t<?php  }?>
\t</td>

</tr>
</table>
</center>
<br/>
<?php
extract(\$search_results);
if (!isset(\$adv_qry))
    \$adv_qry=\"\";
?>

<?php if (\$search_results[\x27ignore_words\x27]){
    \$ignored = \"\";?>
\t<div id=\"common_report\">
\t<?php while (\$thisword=each(\$ignore_words)) {
\t\t\$ignored .= \" \".\$thisword[1];
\t}
\t\$msg = str_replace (\x27%ignored_words\x27, \$ignored, \$sph_messages[\"ignoredWords\"]);
\techo \$msg; ?>
    </div>
<?php  }?>


<?php if (\$search_results[\x27total_results\x27]==0){?>
\t<div id =\"result_report\">
\t\t<?php
\t\t\$msg = str_replace (\x27%query\x27, \$ent_query, \$sph_messages[\"noMatch\"]);
\t\techo \$msg;
\t\t?>
\t</div>
<?php  }?>


<?php if (\$total_results != 0 && \$from <= \$to){?>
\t<div id =\"result_report\">
\t<?php
\t\$result = \$sph_messages[\x27Results\x27];
\t\$result = str_replace (\x27%from\x27, \$from, \$result);
\t\$result = str_replace (\x27%to\x27, \$to, \$result);
\t\$result = str_replace (\x27%all\x27, \$total_results, \$result);
\t\$matchword = \$sph_messages[\"matches\"];
\tif (\$total_results== 1) {
\t\t\$matchword= \$sph_messages[\"match\"];
\t} else {
\t\t\$matchword= \$sph_messages[\"matches\"];
\t}

\t\$result = str_replace (\x27%matchword\x27, \$matchword, \$result);
\t\$result = str_replace (\x27%secs\x27, \$time, \$result);
\techo \$result;
\t?>
\t</div>
<?php  }?>


<?php if (isset(\$search_results[\x27did_you_mean\x27]) && count(\$search_results[\x27did_you_mean\x27]) > 0){ ?>
\t<div id=\"did_you_mean\">
\t<?php echo \"&nbsp; \".\$sph_messages[\x27DidYouMean\x27];?>:
      <?php
        for (\$handled = 0; \$handled < count(\$search_results[\x27did_you_mean_c\x27]); \$handled++) {
          /* find highest count */
          \$highidx = 0;
          \$highval = 0;
          for (\$index = 0; \$index < count(\$search_results[\x27did_you_mean_c\x27]); \$index++) {
            if (\$search_results[\x27did_you_mean_c\x27][\$index] > \$highval) {
              \$highval = \$search_results[\x27did_you_mean_c\x27][\$index];
              \$highidx = \$index;
            }
          }
          /* clear highest value, so it won\x27t be found again */
          \$search_results[\x27did_you_mean_c\x27][\$highidx] = 0;
          if (\$handled > 0)
            print \", \";
          print \"<a href=search.php?query=\".quote_replace(addmarks(\$search_results[\x27did_you_mean\x27][\$highidx])).\"&search=1&lang=\".\$language.\">\".\$search_results[\x27did_you_mean_b\x27][\$highidx].\"</a>\";
        }
        print \" ?\";
      ?>
    </div>
<?php }?>


<div id=\"results\">

<?php if (isset(\$qry_results)) {
?>

<!-- results listing -->

\t<?php foreach (\$qry_results as \$_key => \$_row){
        if (isset(\$domain_name))
            \$last_domain = \$domain_name;
        else
            \$last_domain = \"\";
\t\textract(\$_row);
\t\tif (\$show_query_scores == 0) {
\t\t\t\$weight = \x27\x27;
\t\t} else {
\t\t\t\$weight = \"[\$weight%]\";
\t\t}
\t\t?>
\t\t<?php  if (isset(\$domain_name) && \$domain_name==\$last_domain && \$merge_site_results == 1 && \$domain == \"\") {?>
\t\t<div class=\"idented\">
\t\t<?php }?>
\t\t<b><?php print \$num?>.</b> <?php print \$weight?>
\t\t<a href=\"<?php print \$url?>\" class=\"title\">\t<?php print (\$title?\$title:\$sph_messages[\x27Untitled\x27])?></a><br/>
\t\t<?php if (isset(\$summary) && strlen(\$summary) > 0) {?>
        <div class=\"summary\"><?php print \$summary?></div>
        <?php }?>
\t\t<div class=\"description\"><?php print \$fulltxt?></div>
\t\t<div class=\"url\"><?php print \$url?> - <?php print \$page_size?></div>
\t\t<?php  if (isset(\$domain_name) && \$domain_name==\$last_domain && \$merge_site_results == 1 && \$domain == \"\") {?>
\t\t\t[ <a href=\"<?php print \x27search.php?query=\x27.quote_replace(addmarks(\$query)).\x27&search=1&results=\x27.\$results_per_page.\x27&domain=\x27.\$domain_name?>\">More results from <?php print \$domain_name?></a> ]
\t\t\t</div class=\"idented\">
\t\t<?php }?>
\t\t<br/>
\t<?php  }?>
 </div>
<?php }?>

<!-- links to other result pages-->
<?php if (isset(\$other_pages)) {
\tif (\$adv==1) {
\t\t\$adv_qry = \"&adv=1\";
\t}
\tif (\$type != \"\") {
\t\t\$type_qry = \"&type=\$type\";
\t}
?>
\t<div id=\"other_pages\">
\t<?php print \$sph_messages[\"Result page\"]?>:
\t<?php if (\$start >1){?>
\t\t\t\t<a href=\"<?php print \x27search.php?query=\x27.quote_replace(addmarks(\$query)).\x27&start=\x27.\$prev.\x27&search=1&results=\x27.\$results_per_page.\$type_qry.\$adv_qry.\x27&domain=\x27.\$domain?>\"><?php print \$sph_messages[\x27Previous\x27]?></a>
\t<?php  }?>

\t<?php  foreach (\$other_pages as \$page_num) {
\t\t\t\tif (\$page_num !=\$start){?>
\t\t\t\t\t<a href=\"<?php print \x27search.php?query=\x27.quote_replace(addmarks(\$query)).\x27&start=\x27.\$page_num.\x27&search=1&results=\x27.\$results_per_page.\$type_qry.\$adv_qry.\x27&domain=\x27.\$domain?>\"><?php print \$page_num?></a>
\t\t\t\t<?php } else {?>
\t\t\t\t\t<b><?php print \$page_num?></b>
\t\t\t\t<?php  }?>
\t<?php  }?>

\t<?php if (\$next <= \$pages){?>
\t\t\t<a href=\"<?php print \x27search.php?query=\x27.quote_replace(addmarks(\$query)).\x27&start=\x27.\$next.\x27&search=1&results=\x27.\$results_per_page.\$type_qry.\$adv_qry.\x27&domain=\x27.\$domain?>\"><?php print \$sph_messages[\x27Next\x27]?></a>
\t<?php  }?>

\t</div>

<?php }?>


<div class=\"divline\">
</div>
<div id=\"powered_by\">
<!--If you want to remove this, please donate to the project at http://www.sphider.eu/donate.php-->
<a href=\"http://www.sphider.eu/\"><img src=\"sphider-logo.png\" border=\"0\" style=\"vertical-align: middle\" alt=\"Sphider\"></a>
</div>
</body>
</html>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "search/search.html";
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
        return array (  136 => 50,  130 => 48,  128 => 47,  125 => 46,  117 => 41,  110 => 39,  104 => 38,  98 => 37,  92 => 36,  87 => 34,  78 => 27,  76 => 26,  71 => 24,  65 => 21,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "search/search.html", "/home/vincent/git/sphider-pdo/templates/twig/search/search.html");
    }
}
