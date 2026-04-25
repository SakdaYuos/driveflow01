<?php

/**
 * DriveFlow — Vercel entry point
 * Runtime: vercel-php@0.7.2
 * Laravel 11
 */

define('LARAVEL_START', microtime(true));

// Point Laravel to the correct paths
$_ENV['APP_BASE_PATH'] = dirname(__DIR__);

// Boot vendor autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// Handle the request through Laravel
(require_once dirname(__DIR__) . '/bootstrap/app.php')
    ->handleRequest(Illuminate\Http\Request::capture());
