<?php

return [
    'maxAttemptsPerHour' => env('MAX_RESPONSES_PER_HOUR', 5),
    'randomizedTimestampSampleSize' => env('RANDOMIZED_TIMESTAMP_SAMPLE_SIZE', 10),
    'saltRotationDuration' => env('SALT_ROTATION_DURATION', 3600 * 6),
];
