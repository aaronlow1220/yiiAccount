<?php

use yii\web\Response;

$params = require __DIR__.'/params.php';
$container = require __DIR__.'/container.php';
$urlManager = require __DIR__.'/urlManager.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Asia/Taipei',
    'bootstrap' => ['log'],
    'aliases' => [
        '@v1' => '@app/modules/v1',
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
            'controllerNamespace' => 'v1\controllers',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XFCRIa1-QGeIpXJh5hXwP3G7FaHgGbF9',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'autoRenewCookie' => false,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => $params['db']['dsn'],
            'username' => $params['db']['username'],
            'password' => $params['db']['password'],
            'charset' => 'utf8mb4',
        ],
        'urlManager' => $urlManager,
    ],
    'container' => $container,
    'params' => $params,
];

return $config;
