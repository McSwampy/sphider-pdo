<?php

$db = new PDO(
	'mysql:host=' . constant('settings')['database']['host'] . ';'.
	'dbname='.constant('settings')['database']['database_name'],
	constant('settings')['database']['username'],
	constant('settings')['database']['password']
);