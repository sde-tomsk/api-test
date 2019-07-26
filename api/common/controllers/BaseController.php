<?php

namespace api\common\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Базовый контроллер для работы с REST API
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
abstract class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'create' => ['POST'],         // C - создать
            'index'  => ['GET', 'HEAD'],  // R - получить
            'view'   => ['GET', 'HEAD'],  // R - получить
            'update' => ['PUT', 'PATCH'], // U - изменить
            'delete' => ['DELETE'],       // D - удалить
        ];
    }
}