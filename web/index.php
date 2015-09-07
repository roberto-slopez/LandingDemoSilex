<?php
require_once __DIR__ .'/../vendor/autoload.php';
$app = require __DIR__ .'/../App/app.php';

// Borrar cuando vaya a produccion
$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

$app->run();