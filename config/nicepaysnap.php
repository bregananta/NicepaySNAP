<?php

return [
    'base_url' => env('NICEPAY_BASE_URL', ''),
    'client_id' => env('NICEPAY_CLIENT_ID', ''),
    'client_secret' => env('NICEPAY_CLIENT_SECRET', ''),
    'private_key' => env('NICEPAY_PRIVATE_KEY', ''),
    'dbprocess_url' => env('NICEPAY_DBPROCESS_URL', ''),
    'log' => env('NICEPAY_LOG', true)
];