<?php

namespace common\forms;

use common\forms\base\BaseModel;
use common\helpers\HashHelper;
use common\models\Email;
use common\models\PasswordHash;
use common\models\User;
use common\models\UserEmail;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Модель для регистрации нового пользователя (с использованием пароля)
 * используется для прямой регистрации пользователя на сайте
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class SignupForm extends BaseModel
{
    /**
     * Уникальный почтовый адрес в системе
     * @var string
     */
    public $email;

    /**
     * Пароль используемый для входа, связанный с этим почтовым адресов
     * @var string
     */
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['email', 'string', 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email', 'message' => Yii::t('app', 'Указанный почтовый адрес не является правильным')],
            ['email', 'validateEmail'],
            ['password', 'required'],
            [
                'password',
                'string',
                'length' => [6, 24],
            ],
        ]);
    }

    /**
     * Проверка почтового адреса на уникальность с учётом регистра ввода
     *
     * @param $attribute
     * @param $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validateEmail($attribute, $params)
    {
        $is_exist = Email::find()
                ->andWhere([
                    'lower(value)' => strtolower($this->email)
                ])
                ->count() > 0;

        if ($is_exist) {
            $this->addError($attribute, Yii::t('app', 'Данный почтовый адрес уже используется в системе'));

            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'password' => Yii::t('app', 'Пароль'),
            'email'    => Yii::t('app', 'Адрес электронной почты'),
        ]);
    }

    /**
     * Регистрация пользователя в системе
     * @return bool|mixed
     */
    public function doSave()
    {
        // создаём нового пользователя
        $user = new User();
        $user->save();
        if (!$user->save()) {
            $this->addErrors($user->errors);

            return false;
        }

        // Сохраняем почтовый адрес
        $email = new Email([
            'value' => $this->email
        ]);
        if (!$email->save()) {
            $this->addErrors($email->errors);

            return false;
        }

        // делаем связь между пользователем и почтовым адресом
        $ue = new UserEmail([
            'user_id'  => $user->id,
            'email_id' => $email->id
        ]);
        if (!$ue->save()) {
            $this->addErrors($ue->errors);

            return false;
        }

        $password_hash = HashHelper::generateHash($this->password, $user->auth_key);

        // сохраняем уникальный ключ доступа для авторизации
        $ph = new PasswordHash([
            'password_hash' => $password_hash
        ]);
        if (!$ph->save()) {
            $this->addErrors($ph->errors);

            return false;
        }

        return true;
    }
}
