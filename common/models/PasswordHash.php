<?php

namespace common\models;

use common\models\base\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%password_hash}}".
 *
 * @property string $password_hash Шифрованный пароль
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class PasswordHash extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%password_hash}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['password_hash'], 'required'],
            [['password_hash'], 'string', 'max' => 255],
            [['password_hash'], 'unique'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'password_hash' => Yii::t('app', 'Шифрованный пароль'),
        ]);
    }
}
