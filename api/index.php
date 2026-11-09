<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$storagePath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'storage';
$app->useStoragePath($storagePath);

foreach ([
    $app->storagePath('framework/views'),
    $app->storagePath('framework/cache/data'),
    $app->storagePath('framework/sessions'),
    $app->storagePath('logs'),
    $app->storagePath('app/private'),
    $app->storagePath('app/public'),
] as $directory) {
    if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
        throw new RuntimeException("Unable to create writable storage directory: {$directory}");
    }
}

if (file_exists($maintenance = $app->storagePath('framework/maintenance.php'))) {
    require $maintenance;
}

$app->handleRequest(Request::capture());
