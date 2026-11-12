<?php

return [
    'rate_limit' => env('PUBLIC_API_RATE_LIMIT', 60),
    'cache_max_age' => env('API_CACHE_MAX_AGE', 60),
];
