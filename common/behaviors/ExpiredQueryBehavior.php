<?php

namespace common\behaviors;

use yii\base\Behavior;

/**
 * ExpiredQueryBehavior Используется в ActiveQuery
 *
 * use common\behaviors\ExpiredQueryBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'ExpiredQueryBehavior' => [
 *             'class'            => ExpiredQueryBehavior::className(),
 *         ],
 *     ];
 * }
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class ExpiredQueryBehavior extends Behavior
{
    /**
     * @var string Атрибут с указанием время действия записи
     */
    public $expiredAttribute = 'expired';

    /**
     * Записи, которые уже прошли
     *
     * @return mixed
     */
    public function prepareExpired()
    {
        $now = time();

        return $this->owner
            ->alias($this->owner->alias)
            ->andWhere(['<=', $this->expiredAttribute, $now]);
    }

    /**
     * Записи, которые еще не прошли
     *
     * @return mixed
     */
    public function prepareNotExpired()
    {
        $now = time();
        $a = $this->owner->alias;

        return $this->owner
            ->alias($this->owner->alias)
            ->andWhere(
                [
                    'or',
                    ['IS', $a . '.' . $this->expiredAttribute, null],
                    ['>', $a . '.' . $this->expiredAttribute, $now]
                ]
            );
    }
}
