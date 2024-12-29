<?php

return [
    'serverKey' => env('MIDTRANS_SERVER_KEY'),
    'clientKey' => env('MIDTRANS_CLIENT_KEY'),
    'isProduction' => env('MIDTRANS_IS_PRODUCTION'),
    'snap_url' => env('MIDTRANS_SNAP_URL'),
    'isSanitized' => true,
    'is3ds' => true,
];