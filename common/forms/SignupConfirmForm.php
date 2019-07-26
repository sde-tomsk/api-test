<?php

namespace common\forms;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Модель для регистрации нового пользователя (с использованием пароля)
 * используется для прямой регистрации пользователя на сайте
 * используется подтверждение пароля
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class SignupConfirmForm extends SignupForm
{
    /**
     * Подтверждение пароля
     *
     * @var
     */
    public $password2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['password2', 'required'],
            [
                'password2',
                'string',
                'length' => [6, 24],
            ],
            [
                'password2',
                'compare',
                'compareAttribute' => 'password',
                'message'          => Yii::t('app', 'Подтверждение пароля должно совпадать с паролем'),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'password2' => Yii::t('app', 'Подтверждение пароля'),
        ]);
    }
}
