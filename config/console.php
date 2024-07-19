<?php

$params = require __DIR__.'/params.php';
$container = require __DIR__.'/container.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Asia/Taipei',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@v1' => '@app/modules/v1',
    ],
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => $params['db']['dsn'],
            'username' => $params['db']['username'],
            'password' => $params['db']['password'],
            'charset' => 'utf8mb4',
        ],
    ],
    'controllerMap' => [
        'genmodel' => [
            'class' => 'AtelliTech\Yii2\Utils\ModelGeneratorController',
            'db' => 'db', // db comopnent id default: db
            'path' => '@app/models', // store path of model class file default: @app/models
            'namespace' => 'app\models', // namespace of model class default: app\models
        ],
        'genapi' => [
            'class' => 'AtelliTech\Yii2\Utils\ApiGeneratorController',
            'db' => 'db', // db comopnent id default: db
        ],
        'container' => [
            'class' => 'AtelliTech\Yii2\Utils\ContainerController',
        ],
    ],
    'container' => $container,
    'params' => $params,
];

return $config;
