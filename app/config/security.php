<?php

return [
    // forgotten password settings
    'forgotten_password' => [
        // the max number of requests allowed within the throttle time
        'max_requests' => 5,

        // the minimum time required between max requests, in minutes
        'throttle_time' => 20,
    ]
];
