<?php

return [
    'maxAttemptsPerHour' => env('MAX_RESPONSES_PER_HOUR', 5),
    'randomizedTimestampSampleSize' => env('RANDOMIZED_TIMESTAMP_SAMPLE_SIZE', 10),
    'saltRotationDuration' => env('SALT_ROTATION_DURATION', 3600 * 6),

    // The guaranteed minimum time that answer will be editable
    'editableTime' => env('ANSWER_EDIT_HOURS', 6),
    // A random number of minutes between zero and this value will be added to the guaranteed minimum time,
    // to prevent the answer to be directly associable to any log entries for privacy.
    'editableTime_randomNumberOfMinutesRange' => env('ANSWER_EDIT_RANDOM_MINUTES_RANGE', 120),
];
