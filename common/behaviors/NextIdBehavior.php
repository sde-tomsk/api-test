<?php

namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Query;

/**
 * NextIdBehavior Заполняет следующим значение из последовательности
 *
 * use common\behaviors\NextIdBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'NextIdBehavior' => [
 *             'class'       => NextIdBehavior::className(),
 *             'idAttribute' => 'id',
 *         ],
 *     ];
 * }
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class NextIdBehavior extends AttributeBehavior
{
    /**
     * Атрибут для ID
     * @var string
     */
    public $idAttribute = 'id';

    /**
     * Имя схемы
     * @var string
     */
    public $tableSchema = '';

    /**
     * Имя таблицы
     * @var string
     */
    public $tableName = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->idAttribute],
            ];
        }
    }

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($value = $this->owner->{$this->idAttribute}) {
            return $value;
        } elseif ($this->value instanceof Expression) {
            return $this->value;
        } elseif ($this->value instanceof \Closure) {
            return call_user_func($this->value, $event);
        } else {
            return $this->nextId();
        }
    }

    /**
     * Получить ID для следующей записи
     * @return false|null|string
     */
    public function nextId()
    {
        return (new Query())
            ->select('AUTO_INCREMENT')
            ->from('information_schema.tables')
            ->andWhere([
                'table_schema' => $this->tableSchema,
                'table_name'   => $this->tableName,
            ])
            ->scalar();
    }
}