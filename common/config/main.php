<?php
return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class'     => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
        ],
        // other default components here..
        'jwt'   => [
            'class' => 'sizeg\jwt\Jwt',
            'key'   => 'test-api-secret-key',
        ],
        'db'    => [
            'class'               => 'yii\db\Connection',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 0,
            'dsn'                 => 'mysql:host=localhost;port=3307;dbname=api-test',
            'charset'             => 'utf8',
        ],
        'view' => [
            'renderers' => [
                'twig' => [
                    'class'     => 'yii\twig\ViewRenderer',
                    'options'   => [
                        'auto_reload' => true,
                    ],
                    'globals'   => [
                        'html'  => 'yii\bootstrap\Html',
                        'array' => 'yii\helpers\ArrayHelper',
                    ],
                    'uses'      => ['yii\bootstrap'],
                    'functions' => [
                        't' => 'Yii::t',
                        new \Twig_SimpleFunction('call', function ($className, $method, $arguments = null) {
                            $callable = [$className, $method];
                            if ($arguments === null) {
                                return call_user_func($callable);
                            }

                            return call_user_func_array($callable, $arguments);
                        }),
                        new \Twig_SimpleFunction('execute', function ($function, $arguments = null) {
                            if ($arguments === null) {
                                return call_user_func($function);
                            }

                            return call_user_func_array($function, $arguments);
                        }),
                    ],
                ],
            ],
        ],
    ],
];
