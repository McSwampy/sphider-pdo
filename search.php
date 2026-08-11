<?php

/*******************************************
 * Sphider Version 2.0.x
 * This program is licensed under the GNU GPL.
 *
 * Authors:
 * Ando Saabas          ando(a t)cs.ioc.ee
 * McSwampy             sylph.co.za
 *******************************************/

error_reporting(E_ALL | E_STRICT);

try {

    // Define global settings
    define('settings', include __DIR__.'/settings/config.php');

    require_once __DIR__ . '/framework/autoload.php';
    require_once __DIR__ . '/include/autoload.php';

    $settingsDir = __DIR__ . '/settings';

    require_once $settingsDir . '/database.php';
    require_once $settingsDir . '/conf.php';

    /*
     * Request parameters
     */
    $query    = sanitize($_POST['query'] ?? $_GET['query'] ?? '');
    $search   = sanitize($_POST['search'] ?? $_GET['search'] ?? '');
    $language = sanitize($_POST['lang'] ?? $_GET['lang'] ?? 'en');
    $start    = sanitize($_POST['start'] ?? $_GET['start'] ?? '');
    $domain   = sanitize($_POST['domain'] ?? '');
    $type     = sanitize($_POST['type'] ?? '');
    $catid    = sanitize($_POST['catid'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $results  = sanitize($_POST['results'] ?? '');
    $adv      = sanitize($_POST['adv'] ?? '');

    \Language\Manager::InitLang($language);

    /*
     * Default values.
     */
    $searchResults  = [];
    $catInfo        = [];
    $resultsPerPage = $resultsPerPage ?? 10;

    /*
     * Ignore the search prompt itself.
     */
    if (
        !empty($query) &&
        isset($sph_messages['SearchPrompt']) &&
        strcasecmp($query, $sph_messages['SearchPrompt']) === 0
    ) {
        $query = '';
    }

    /*
     * Validate search type.
     */
    $allowedTypes = ['or', 'and', 'phrase'];

    if (!in_array($type, $allowedTypes, true)) {
        $type = 'and';
    }

    /*
     * Validate domain.
     */
    if (
        !empty($domain) &&
        preg_match('/[^a-z0-9.\-]+/i', $domain)
    ) {
        $domain = '';
    }

    /*
     * Results per page.
     */
    if (!empty($results) && is_numeric($results)) {
        $resultsPerPage = (int) $results;
    }

    /*
     * Category values.
     */
    $catid = (int) $catid;

    if (!is_numeric($category) || (int) $category <= 0) {
        $category = '';
    } else {
        $category = (int) $category;
    }

    if ($catid > 0) {
        $tpl_['category'] = sql_fetch_all(
            'SELECT category
             FROM categories
             WHERE category_id = :catid',
            [
                ':catid' => $catid
            ]
        );
    }

    /*
     * Check whether categories exist.
     */
    $countLevel0 = sql_fetch_all(
        'SELECT COUNT(*)
         FROM categories
         WHERE parent_num = :parent',
        [
            ':parent' => 0
        ]
    );

    $hasCategories = !empty($countLevel0)
        ? (int) $countLevel0[0][0]
        : 0;

    /*
     * Time helpers.
     */
    date_default_timezone_set('Etc/UCT');

    function getmicrotime(): float
    {
        return (float)hrtime(true);
    }

    function poweredby(): string
    {
        return \Templating\Manager::loadTemplate('powered-by.html');
    }

    function saveToLog($query, $elapsed, $results)
    {
        global $db;
	    $prefix = constant('settings')['database']['table_prefix'];

        $results = ($results === '') ? 0 : (int) $results;

        $statement = $db->prepare(
            'INSERT INTO ' . $prefix . 'query_log
                (query, time, elapsed, results)
             VALUES
                (:query, :tstamp, :elapsed, :results)'
        );

        $statement->execute([
            ':query'   => $query,
            ':tstamp'  => date('Y-m-d H:i:s'),
            ':elapsed' => $elapsed,
            ':results' => $results
        ]);
    }

    /*
     * Search handling.
     */
    $search = (int) $search;
    $start  = (int) $start;

    if ($search !== 1 || $query === '') {
        $search = 0;
    }

    switch ($search) {
        case 1:
            /*
             * Clean search query.
             */
            if ($type !== 'phrase') {
                $query = str_replace(
                    ['"', '&quot;', '&#39;'],
                    ' ',
                    $query
                );
            }

            $query = str_replace(
                [
                    '&amp;',
                    '&lt;',
                    '&gt;',
                    '#',
                    '&',
                    ';',
                    "'",
                    '*',
                    '%',
                    '\\'
                ],
                ' ',
                $query
            );

            /*
             * Reject null bytes.
             */
            if (strpos($query, "\0") !== false) {
                $query = '';
            }

            /*
             * Execute search.
             */
            if ($query !== '') {
                $searchResults = get_search_results(
                    $query,
                    $start,
                    $category,
                    $type,
                    $resultsPerPage,
                    $domain
                );
            }

            break;

        default:
            /*
             * Display categories on the initial search page.
             */
            if ($show_categories) {
                if ($catid > 0) {
                    $catInfo = get_category_info($catid);
                } else {
                    $catInfo = get_categories_view();
                }
            }

            break;
    }

    /*
     * Render page.
     */
    \Templating\Manager::ShowTemplate(
        'search/search.html',
        [
            'settings' => constant('settings'),
            'query' => $query,
            'results_per_page' => $resultsPerPage,
            'cat_info' => $catInfo,
            'sph_messages' => \Language\Manager::$langStrings,
            'REQUEST' => $_REQUEST,
            'results_count_options' => constant('settings')['results_per_page'] ?? [10,20,50]
        ]
    );

} catch (\Throwable $thrown) {

    \Templating\Manager::ShowThrowable($thrown, 'Search Main Page');

}