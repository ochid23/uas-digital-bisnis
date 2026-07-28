<?php

$serverKey = trim(env('MIDTRANS_SERVER_KEY', ''));
$isProdEnv = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
$isProdKey = str_starts_with($serverKey, 'Mid-server-');

return [
    'server_key' => $serverKey,
    'client_key' => trim(env('MIDTRANS_CLIENT_KEY', '')),
    'is_production' => $isProdKey || $isProdEnv,
    'is_sanitized' => true,
    'is_3ds' => true,
];