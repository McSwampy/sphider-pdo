<?php

/**
 * Full site configuration file.
 * Saved to constant value of 'settings' for easy retrieval
 * 
 * @author McSwampy <mcswampy@sylph.co.za>
 * @copyright 2026 Sylph Syndicate
 * @since 2.0.x
 * 
 */
return [
    'version' => '2.0.0',
    'version_name' => 'beetroot',
    'site_name' => 'Sphider',
    'default_language' => 'en',
    'results_per_page' => [
        10, 20, 50, 100, 200
    ],
    'database' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'asdf',
        'database_name' => 'sphider',
        'table_prefix' => ''
    ],
    'admin' => [
        'email_account' => 'admin@localhost'
    ]
];