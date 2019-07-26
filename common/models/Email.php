<?php

namespace common\models;

use common\models\base\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%email}}".
 *
 * @property string $value Название почтового адреса
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class Email extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%email}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['value'], 'string', 'max' => 255],
            [['value'], 'unique'],
            ['value', 'email'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::rules(), [
            'value' => Yii::t('app', 'Название почтового адреса'),
        ]);
    }
}
