<?php

namespace common\models;

use common\helpers\TextHelper;
use common\models\base\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%book}}".
 *
 * @property string $name Название кники
 * @property string $code Уникальный код кники
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class Book extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['name', 'code'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 32],
            [['code'], 'unique'],
            [
                ['name'],
                'filter',
                'filter' => function ($value) {
                    return TextHelper::normalizeWhitespace($value);
                }
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'name' => Yii::t('app', 'Название книги'),
            'code' => Yii::t('app', 'Уникальный код книги'),
        ]);
    }
}
