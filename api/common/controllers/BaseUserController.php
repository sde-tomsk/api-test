<?php

namespace api\common\controllers;

use sizeg\jwt\JwtHttpBearerAuth;

/**
 * Базовый контроллер для работы с REST API только авторизированным пользоватеям
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
abstract class BaseUserController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];

        return $behaviors;
    }

}