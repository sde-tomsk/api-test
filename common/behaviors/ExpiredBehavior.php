<?php

namespace common\behaviors;

use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;

/**
 * ExpiredBehavior автоматически заполняет указанные атрибуты временем действия записи
 *
 * use common\behaviors\ExpiredBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'ExpiredBehavior' => [
 *             'class'            => ExpiredBehavior::className(),
 *             'expiredAttribute' => 'expired',
 *         ],
 *     ];
 * }
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class ExpiredBehavior extends AttributeBehavior
{
    /**
     * Атрибут с указанием время действия записи
     * @var string
     */
    public $expiredAttribute = 'expired';

    /**
     * Время жизни записи
     * @var int
     */
    public $lifetime = 86400; // one day

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->expiredAttribute],
            ];
        }
    }

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($this->value instanceof Expression) {
            return $this->value;
        } else {
            if ($this->value !== null) {
                return call_user_func($this->value, $event);
            } else {
                return time() + $this->lifetime;
            }
        }
    }

    /**
     * Проверяем является ли текущая запись устаревшей
     * @return bool
     */
    public function isExpired()
    {
        return $this->owner{$this->expiredAttribute} <= time();
    }
}