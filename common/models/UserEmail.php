<?php

namespace common\models;

use common\models\base\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user_email}}".
 *
 * @property int $user_id Пользователь
 * @property int $email_id Почтовый адрес пользователя
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class UserEmail extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_email}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['user_id', 'email_id'], 'required'],
            [['user_id', 'email_id'], 'integer'],
            [
                ['user_id', 'email_id'],
                'unique',
                'targetAttribute' => ['user_id', 'email_id'],
                'message'         => Yii::t('app', 'Почтовый адрес уже привязан к пользователю')
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'user_id'  => Yii::t('app', 'Пользователь'),
            'email_id' => Yii::t('app', 'Почтовый адрес пользователя'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmail()
    {
        return $this->hasOne(Email::class, ['id' => 'email_id']);
    }
}
