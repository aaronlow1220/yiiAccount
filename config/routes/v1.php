<?php

return [
    'GET /apidoc' => 'v1/open-api-spec/index',
    [ // Account
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/account-names',
        'pluralize' => false,
        'except' => ['index', 'delete'],
        'extraPatterns' => [
            'POST search' => 'search',
            'POST new-record'=> 'new-record',
        ],
    ],
];
