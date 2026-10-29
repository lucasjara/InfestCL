<?php

return [
    'rate_limit' => (int) env('PUBLIC_API_RATE_LIMIT', 60),
    'cache_max_age' => (int) env('PUBLIC_API_CACHE_MAX_AGE', 60),
];
