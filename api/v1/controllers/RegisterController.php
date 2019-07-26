<?php

namespace api\v1\controllers;

use api\common\controllers\BaseController;
use common\forms\SignupForm;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;

/**
 * REST API для регистрации пользователя
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class RegisterController extends BaseController
{
    /**
     * @SWG\Post(path="/register",
     *      tags = {"user"},
     *      summary = "регистрации пользователя",
     *      description = "Регистрации нового пользователя в системе",
     *      produces = {"application/json", "application/xml"},
     *      @SWG\Parameter(
     *          in = "body",
     *          name = "credentials",
     *          description = "Регистрационные данные пользователя",
     *          required = true,
     *          @SWG\Schema(ref="#/definitions/UserCredentials")
     *      ),
     *      @SWG\Response(
     *          response = 201,
     *          description = "Created"
     *      ),
     *      @SWG\Response(
     *          response = 400,
     *          description = "Bad Request"
     *      ),
     *      @SWG\Response(
     *          response = 409,
     *          description = "Conflict"
     *      )
     *  )
     */
    public function actionCreate()
    {
        $post = Yii::$app->request->getBodyParams();

        $signup = new SignupForm();

        if ($signup->load($post, '')) {
            if ($signup->save()) {

                Yii::$app->response->setStatusCode(201);

                return [
                    'message' => 'OK'
                ];
            } else {
                return [
                    'message' => 'ERROR',
                    'errors'  => $signup->errors
                ];
            }
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'Не указаны обязательные параметры'));
        }
    }
}