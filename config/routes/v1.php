<?php

return [
    'GET /apidoc' => 'v1/open-api-spec/index',
    [ // Account
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'account' => 'v1/account',
        ],
        'except' => ['index', 'delete'],
        'extraPatterns' => [
        ],
    ],
];