<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log', 'v1'],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl'   => '',
            'parsers'   => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'suffix'          => '',
            'rules'           => [
                [
                    'pattern' => '',
                    'route'   => 'site/index'
                ],
                [
                    'pattern' => '<controller>/<action>',
                    'route'   => '<controller>/<action>',
                    'suffix'  => '.html',
                ],
            ],
        ],
    ],
    'homeUrl'             => '/',
    'modules'             => [
        'v1' => [
            'class' => 'api\v1\Module'
        ],
    ],
    'params'              => $params,
];
