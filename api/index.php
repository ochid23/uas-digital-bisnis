<?php

// Create storage directories in /tmp for Vercel Serverless environment
$directories = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

putenv('APP_STORAGE=/tmp/storage');
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_SERVER['APP_STORAGE'] = '/tmp/storage';

putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
$_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';

putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';

putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
$_ENV['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';

putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
$_ENV['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes.php';

putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
$_ENV['APP_EVENTS_CACHE'] = '/tmp/bootstrap/cache/events.php';

// Fallback APP_KEY if not set in Vercel Environment Variables
if (!getenv('APP_KEY') && empty($_ENV['APP_KEY'])) {
    putenv('APP_KEY=base64:pymWo3UMJ9ZJs6SZYOy6TgbVbN0X+t8hB2JUmyGfMtk=');
    $_ENV['APP_KEY'] = 'base64:pymWo3UMJ9ZJs6SZYOy6TgbVbN0X+t8hB2JUmyGfMtk=';
    $_SERVER['APP_KEY'] = 'base64:pymWo3UMJ9ZJs6SZYOy6TgbVbN0X+t8hB2JUmyGfMtk=';
}

// Fallback APP_DEBUG=true if not explicitly set to false
if (!getenv('APP_DEBUG') && !isset($_ENV['APP_DEBUG'])) {
    putenv('APP_DEBUG=true');
    $_ENV['APP_DEBUG'] = 'true';
    $_SERVER['APP_DEBUG'] = 'true';
}

// Forward request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
