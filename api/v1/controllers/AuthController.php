<?php

namespace api\v1\controllers;

use api\common\controllers\BaseController;
use common\forms\LoginForm;
use common\models\User;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use common\models\Jwt as JwtToken;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * REST API для работа с книгами
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class AuthController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class'    => JwtHttpBearerAuth::class,
            'optional' => [
                'create',
            ],
        ];

        return $behaviors;
    }

    /**
     * @SWG\Post(path="/auth",
     *      tags = {"user"},
     *      summary = "Авторизация пользователя",
     *      description = "Авторизация пользователя, при успешной авторизации возвращается JWT токен,
    в качетве параметра авторизации нужнро передавать Authorization: Basic {base64_encode(email:password)}
    Например: Basic ZGVtb0BkZW1vLnJ1OjEyMzQ1Njc4 что соответствует email = demo@demo.ru, password = 12345678",
     *      produces = {"application/json", "application/xml"},
     *      @SWG\Parameter(
     *          name = "authorization",
     *          in = "header",
     *          description = "Ключ авторизации",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "Created"
     *      ),
     *      @SWG\Response(
     *         response = 400,
     *         description = "Bad Request"
     *      ),
     *      @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *      )
     *  )
     */
    public function actionCreate()
    {
        $username = Yii::$app->request->getAuthUser();
        $password = Yii::$app->request->getAuthPassword();

        $login = new LoginForm();
        if ($login->load(['email' => $username, 'password' => $password], '')) {
            /** @var User $user */
            if ($user = $login->login()) {
                $jwtToken = new JwtToken([
                    'user_id' => $user->getId()
                ]);
                if (!$jwtToken->save()) {
                    throw new ServerErrorHttpException(Yii::t('app', 'Ошибка сохранения данных'));
                }

                $signer = new Sha256();
                /** @var Jwt $jwt */
                $jwt = Yii::$app->jwt;
                $token = $jwt
                    ->getBuilder()
                    ->setIssuer('http://localhost')// для тестового задания
                    ->setAudience('http://localhost')// для тестового задания
                    ->setId($jwtToken->token, true)
                    ->setIssuedAt($jwtToken->created_at)
                    ->setExpiration($jwtToken->expired)
                    ->set('uid', $user->id)
                    ->sign($signer, $jwt->key)
                    ->getToken();

                return [
                    'message' => 'OK',
                    'user_id' => $user->getId(),
                    'token'   => (string)$token
                ];
            } else {
                throw new BadRequestHttpException(Yii::t('app', 'Неправильно указан почтовый адрес или пароль'));
            }
        } else {
            throw new BadRequestHttpException(Yii::t('app', 'Не указаны обязательные параметры'));
        }
    }
}