<?php

return [
    'maxAge' => env('CDN_ENDPOINT_CACHE_MAX_AGE', 3600),
    'trustedIps' => env('CDN_TRUSTED_IPS'),
];
