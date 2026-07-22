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

// Fallback APP_DEBUG to true if not set
if (!getenv('APP_DEBUG') && !isset($_ENV['APP_DEBUG'])) {
    putenv('APP_DEBUG=true');
    $_ENV['APP_DEBUG'] = 'true';
    $_SERVER['APP_DEBUG'] = 'true';
}

// Fallback Database configuration to Neon PostgreSQL if not set
if (!getenv('DB_CONNECTION') && empty($_ENV['DB_CONNECTION'])) {
    putenv('DB_CONNECTION=pgsql');
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_SERVER['DB_CONNECTION'] = 'pgsql';

    $neonUrl = 'postgresql://neondb_owner:npg_cqBHvatNV08J@ep-red-lake-avi4xon4-pooler.c-11.us-east-1.aws.neon.tech/neondb?sslmode=require';
    putenv("DB_URL={$neonUrl}");
    $_ENV['DB_URL'] = $neonUrl;
    $_SERVER['DB_URL'] = $neonUrl;

    putenv('DB_HOST=ep-red-lake-avi4xon4-pooler.c-11.us-east-1.aws.neon.tech');
    $_ENV['DB_HOST'] = 'ep-red-lake-avi4xon4-pooler.c-11.us-east-1.aws.neon.tech';
    $_SERVER['DB_HOST'] = 'ep-red-lake-avi4xon4-pooler.c-11.us-east-1.aws.neon.tech';

    putenv('DB_PORT=5432');
    $_ENV['DB_PORT'] = '5432';
    $_SERVER['DB_PORT'] = '5432';

    putenv('DB_DATABASE=neondb');
    $_ENV['DB_DATABASE'] = 'neondb';
    $_SERVER['DB_DATABASE'] = 'neondb';

    putenv('DB_USERNAME=neondb_owner');
    $_ENV['DB_USERNAME'] = 'neondb_owner';
    $_SERVER['DB_USERNAME'] = 'neondb_owner';

    putenv('DB_PASSWORD=npg_cqBHvatNV08J');
    $_ENV['DB_PASSWORD'] = 'npg_cqBHvatNV08J';
    $_SERVER['DB_PASSWORD'] = 'npg_cqBHvatNV08J';

    putenv('DB_SSLMODE=require');
    $_ENV['DB_SSLMODE'] = 'require';
    $_SERVER['DB_SSLMODE'] = 'require';
}

// Forward request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
