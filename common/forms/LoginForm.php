<?php

namespace common\forms;

use common\helpers\HashHelper;
use common\models\PasswordHash;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Форма авторизаци пользователя на сайте
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class LoginForm extends Model
{
    /**
     * Почтовый адрес пользователя
     * @var string
     */
    public $email;

    /**
     * Пароль пользователя
     * @var string
     */
    public $password;

    /**
     * Авторизуемый пользователь
     * @var User
     */
    private $_user = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email'    => Yii::t('app', 'Почтовый адрес пользователя'),
            'password' => Yii::t('app', 'Пароль пользователя'),
        ];
    }

    /**
     * Проверка введённоно пароля пользователем
     *
     * @param string $attribute название атрибута
     * @param array $params параметры правила
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validatePassword($attribute, $params)
    {
        $this->_user = null;

        if (!$this->hasErrors()) {

            /** @var User $user */
            $user = User::find()
                ->prepareEmail($this->email)
                ->one();

            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'Не правильно указан логин или пароль'));

                return false;
            }

            $password_hash = HashHelper::generateHash($this->password, $user->auth_key);

            $is_valid = PasswordHash::find()
                    ->andWhere(['password_hash' => $password_hash])
                    ->count() > 0;

            if (!$is_valid) {
                $this->addError($attribute, Yii::t('app', 'Не правильно указан логин или пароль'));

                return false;
            }

            $this->_user = $user;
        }
    }

    /**
     * Авторизация пользователя
     * Возвращает User::class если пользователь успешно авторизовался в противном случае FALSE
     *
     * @return User|false
     */
    public function login()
    {
        if ($this->validate()) {
            if (Yii::$app->user->login($this->_user)) {
                return $this->_user;
            } else {
                return false;
            }
        }

        return false;
    }
}
