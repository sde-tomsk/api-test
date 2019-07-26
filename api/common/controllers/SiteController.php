<?php

namespace api\common\controllers;

use yii\rest\Controller;

/**
 * Главная страница
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
abstract class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Главная страница
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return [
            'status' => 'ok'
        ];
    }
}
