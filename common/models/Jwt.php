<?php

namespace common\models;

use common\behaviors\ExpiredBehavior;
use common\behaviors\IpBehavior;
use common\behaviors\UniqueHashBehavior;
use common\models\base\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%jwt}}".
 *
 * @property string $token Ключ
 * @property int $expired Время жизни ключа
 * @property int $user_id Пользователь с кем связан токен
 * @property int $ip2long IP Адрес с которого был создан токен
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class Jwt extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%jwt}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['user_id'], 'required'],
            [['expired', 'user_id', 'ip2long'], 'integer'],
            [['token'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'ExpiredBehavior' => [
                'class'            => ExpiredBehavior::class,
                'expiredAttribute' => 'expired',
            ],
            'IpBehavior'      => [
                'class' => IpBehavior::class,
            ],
            'HashBehavior'    => [
                'class'         => UniqueHashBehavior::class,
                'hashAttribute' => 'token',
                'hashLength'    => 32
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'token'   => Yii::t('app', 'Ключ'),
            'expired' => Yii::t('app', 'Время жизни ключа'),
            'user_id' => Yii::t('app', 'Пользователь с кем связан токен'),
            'ip2long' => Yii::t('app', 'IP Адрес с которого был создан токен'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
