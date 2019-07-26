<?php

namespace common\behaviors;

use common\helpers\TextHelper;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * UniqueHashBehavior автоматически заполняет указанный атрибут уникальным восьмизначным значение
 *
 * use common\behaviors\UniqueHashBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'UniqueHashBehavior' => [
 *             'class'         => UniqueHashBehavior::className(),
 *             'hashAttribute' => 'hash',
 *             'hashLength'    => 8
 *         ],
 *     ];
 * }
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class UniqueHashBehavior extends AttributeBehavior
{
    /**
     * Атрибут с указанием уникального хеша
     * @var string
     */
    public $hashAttribute = 'hash';

    /**
     * Длина генерируемго хеша
     * @var int
     */
    public $hashLength = 8;

    /**
     * Алгоритм формирования строки
     * @var string
     */
    public $type = 'security';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->hashAttribute],
            ];
        }
    }

    /**
     * @inheritdoc
     * @param \yii\base\Event $event
     * @return mixed|null|string|Expression
     * @throws \yii\base\Exception
     */
    protected function getValue($event)
    {
        if ($this->value instanceof Expression) {
            return $this->value;
        } else {
            if ($this->value !== null) {
                return call_user_func($this->value, $event);
            } else {
                return $this->generateHash();
            }
        }
    }

    /**
     * Генерация уникального ключа
     * @return null|string
     * @throws \yii\base\Exception
     */
    public function generateHash()
    {
        $randomString = null;

        do {
            $randomString = $this->generateString();
        } while ($this->owner->find()->where([$this->hashAttribute => $randomString])->count() > 0);

        return $randomString;
    }

    /**
     * Сгенерировать ключ указанной длины
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateString()
    {
        return Yii::$app->getSecurity()->generateRandomString($this->hashLength);
    }
}
