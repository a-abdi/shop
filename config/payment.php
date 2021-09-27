<?php

return [
    'X-API-KEY' => env('PAYMENT_X_API_KEY', false),

    'X-SANDBOX' => env('PAYMENT_X_SANDBOX'),

    'create_url' => env('PAYMENT_CREATE_URL'),
    
    'verify_url' => env('PAYMENT_VERIFY_URL')
];