<?php
/*******************************************
* Sphider Version 2.0.x
* This program is licensed under the GNU GPL.
* Authors:
* Ando Saabas          ando(a t)cs.ioc.ee
* McSwampy             sylph.co.za
********************************************/

error_reporting (E_ALL | E_STRICT);

try {

    // Load framework on start. Includes composer vendor libraries
    require_once __DIR__.'/framework/autoload.php';
    
    require_once __DIR__.'/include/autoload.php';
    $template_dir = "./templates";
    $settings_dir = "./settings";
    $language_dir = "./languages";

    require_once("$settings_dir/database.php");
    require_once("$settings_dir/conf.php");

    $query = sanitize($_POST['query'] ?? $_GET['query'] ?? '');
    $search = sanitize($_POST['search'] ?? $_GET['search'] ?? '');
    $language = sanitize($_POST['lang'] ?? $_GET['lang'] ?? 'en');
    $start = sanitize($_POST['start'] ?? $_GET['start'] ?? '');
    $domain = sanitize($_POST['domain'] ?? '');
    $type = sanitize($_POST['type'] ?? '');
    $catid = sanitize($_POST['catid'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $results = sanitize($_POST['results'] ?? '');
    $adv = sanitize($_POST['adv'] ?? '');
    
    require_once("$language_dir/$language-language.php");

    if (
        !empty($query) ||
        isset($sph_messages['SearchPrompt']) &&
        strcasecmp($query, $sph_messages['SearchPrompt']) == 0
    ) {
        $query = '';
    }

    if (file_exists("$template_dir/$template/header_$language.html")) {
        include_once("$template_dir/$template/header_$language.html");
    } else {
        require_once("$template_dir/$template/header.html");
    }

    if (
        empty($type) ||
        (
            $type != "or" &&
            $type != "and" &&
            $type != "phrase"
        )
    ) {
        $type = 'and';
    }

    if (
        !empty($domain) &&
        preg_match("/[^a-z0-9-.]+/", $domain)
    ) {
        $domain = '';
    }

    if (!empty($results)) {
        $results_per_page = $results;
    }

    if (empty((int)$catid)) {
        $catid = 0;
    }

    if (empty((int)$category)) {
        $category = '';
    }

    if ($catid > 0) {
        $tpl_['category'] = sql_fetch_all('SELECT category FROM categories WHERE category_id=:catid', array(':catid' => (int)$_REQUEST['catid']));
    }

    $count_level0 = sql_fetch_all('SELECT count(*) FROM categories WHERE parent_num=:parent', array(':parent' => 0));
    $has_categories = 0;

    if ($count_level0)
        $has_categories = $count_level0[0][0];

    // require_once("$template_dir/$template/search_form.html");

    date_default_timezone_set("Etc/UCT");

    function getmicrotime() {
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
    }

    function poweredby() {
        global $sph_messages;
        //If you want to remove this, please donate to the project at http://www.sphider.eu/donate.php
        print $sph_messages['Powered by'] . '<a href="http://www.sphider.eu/"><img src="sphider-logo.png" border="0" style="vertical-align: middle" alt="Sphider"></a>';
    }

    function saveToLog($query, $elapsed, $results) {
        global $db;

        if ($results == "")
            $results = 0;
        $stat = $db->prepare("insert into ".TABLE_PREFIX."query_log (query, time, elapsed, results) values (:query, :tstamp, :elapsed, :results)");
        $stat->execute(array(':query' => $query, ':tstamp' => date("Y-m-d H:i:s"), ':elapsed' => $elapsed, ':results' => $results));
    }


    if (!isset($search) || strlen($query) == 0)
        $search = 0;
    if (!isset($start))
        $start = 0;
    switch ($search) {
    case 1:
        if (!isset($results))
            $results = "";
        if ($type != "phrase") {
            $query = str_replace("\"", " ", $query);
            $query = str_replace("&quot;", " ", $query);
            $query = str_replace("&#39;", " ", $query);
        }
        $query = str_replace("&amp;", " ", $query);
        $query = str_replace("&lt;", " ", $query);
        $query = str_replace("&gt;", " ", $query);
        $query = str_replace("#", " ", $query);
        $query = str_replace("&", " ", $query);
        $query = str_replace(";", " ", $query);
        $query = str_replace("'", " ", $query);
        $query = str_replace("*", " ", $query);
        $query = str_replace("%", " ", $query);
        $query = str_replace("\\", " ", $query);
        if (strpos($query, '\0') != FALSE)
            $query = "";
        $search_results = get_search_results($query, $start, $category, $type, $results, $domain);
        // require("$template_dir/$template/search_results.html");
        break;
    default:
        if ($show_categories) {
            if (isset($_REQUEST['catid']) && $_REQUEST['catid'] && is_numeric($catid)) {
                $cat_info = get_category_info($catid);
            } else {
                $cat_info = get_categories_view();
            }
        }
        break;
    }

    \Templating\Manager::ShowTemplate(
        'search/search.html',
        [
            'query' => $query,
            'results_per_page' => $results_per_page,
            'cat_info' => $cat_info,
            'sph_messages' => $sph_messages,
            'REQUEST' => $_REQUEST
        ]
    );

} catch (\Throwable $thrown) {

    try {
        \Templating\Manager::ShowTemplate(
            'admin-error.html',
            [
                'exception' => $thrown,
                'error_source_name' => 'Search Main Page'
            ]
        );
    } catch (\Throwable $t) {
        var_dump($t);
    }
}
