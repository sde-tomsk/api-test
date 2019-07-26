<?php

namespace api\common\components;

use Yii;
use yii\web\Response;

/**
 * Обработка ошибок от REST API
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class ApiErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // reset parameters of response to avoid interference with partially created response data
            // in case the error occurred while sending the response.
            $response->isSent = false;
            $response->stream = null;
            $response->data = null;
            $response->content = null;
        } else {
            $response = new Response();
        }

        // ошибка в API
        $response->format = Response::FORMAT_JSON;
        $response->data = $this->convertExceptionToArray($exception);

        if (YII_DEBUG) {
            if (Yii::$app->controller) {
                $response->data['route'] = Yii::$app->controller->route;
            }
        }

        $response->setStatusCodeByException($exception);

        $response->send();
    }
}