<?php

ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('error_log', "/var/www/Rest-API/php_error_log");

require_once "controllers/routes.php";
require_once "config.php";

$index = new RoutesController();
$index -> index();