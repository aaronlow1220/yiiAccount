<?php

return [
    'GET /apidoc' => 'v1/open-api-spec/index',
    [ // Account
        'class' => 'yii\rest\UrlRule',
        'controller' =>'v1/account',
        'pluralize' => false,
        'except' => ['index', 'delete'],
        'extraPatterns' => [
            'GET haha' => 'hello',
        ],
    ],
];