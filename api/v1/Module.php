<?php

namespace api\v1;

use Yii;
use api\common\components\ApiErrorHandler;
use yii\base\BootstrapInterface;
use yii\web\Response;

/**
 * Модуль для API версия 1.0
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $handler = new ApiErrorHandler();
        Yii::$app->set('errorHandler', $handler);

        // необходимо вызывать register, это обязательный метод для регистрации обработчика
        $handler->register();
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $urlManager = Yii::$app->getUrlManager();

        $rules = require(__DIR__ . '/Rules.php');

        $urlManager->addRules($rules);

        Yii::$app->set('response', [
            'class'         => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->format == Response::FORMAT_JSON) {
                    if (is_array($response->data)) {
                        $response->data['code'] = $response->statusCode;
                        unset($response->data['status']);
                    }
                    $code = Yii::$app->request->get('suppress_response_code');
                    if (!in_array(strtolower($code), array('1', 'on', 'true'))) {
                        $response->statusCode = 200;
                    }
                }
            },
        ]);
    }
}