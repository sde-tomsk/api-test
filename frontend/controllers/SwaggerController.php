<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Просмотр страницы документации
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class SwaggerController extends Controller
{
    /**
     * @return mixed
     */
    public function actionHelp()
    {
        return $this->render('help.twig');
    }
}