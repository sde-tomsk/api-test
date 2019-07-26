<?php
/**
 * Настройки маршрутизации протокола
 *
 * 'POST <controller:[\w-]+>s'           => '<controller>/create',
 * '<controller:[\w-]+>s'                => '<controller>/index',
 * '<controller:[\w-]+>/<id:\d+>'        => '<controller>/view',
 * 'PUT <controller:[\w-]+>/<id:\d+>'    => '<controller>/update',
 * 'DELETE <controller:[\w-]+>/<id:\d+>' => '<controller>/delete',
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
return [
    // общие настройки
    'GET v1/<controller:[\w-]+>'             => 'v1/<controller>/index',
    'GET v1/<controller:[\w-]+>/<id:\w+>'    => 'v1/<controller>/view',
    'DELETE v1/<controller:[\w-]+>/<id:\w+>' => 'v1/<controller>/delete',
    'POST v1/<controller:[\w-]+>'            => 'v1/<controller>/create',
    'PUT v1/<controller:[\w-]+>/<id:\w+>'    => 'v1/<controller>/update',
];