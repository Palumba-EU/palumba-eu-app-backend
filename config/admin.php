<?php

return [
    'localParty' => [
        'visible' => env('ADMIN_LOCAL_PARTY_VISIBLE', true),
    ],
    'party' => [
        'label' => env('ADMIN_PARTY_LABEL', 'Electable party'),
        'pluralLabel' => env('ADMIN_PARTY_PLURAL_LABEL', 'Electable parties'),
    ],
    'country' => [
        'label' => env('ADMIN_COUNTRY_LABEL', 'Country or Region'),
        'pluralLabel' => env('ADMIN_COUNTRY_PLURAL_LABEL', 'Countries and Regions'),
    ],
];
