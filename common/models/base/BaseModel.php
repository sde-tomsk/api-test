<?php

namespace common\models\base;

use common\models\User;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Базовая модель для работы
 *
 * @property int $id
 * @property int $created_at Когда добавлено
 * @property int $created_by Кем добавлено
 * @property int $updated_at Кем обновлено
 * @property int $updated_by Когда обновлено
 * @property int $status Статус записи
 *
 * @property User $updatedBy
 * @property User $createdBy
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
abstract class BaseModel extends \yii\db\ActiveRecord
{
    /**
     * Имя базового ActiveQuery класса
     * @var string
     */
    protected static $queryClass = 'common\models\base\BaseQuery';

    /**
     * @var array
     */
    public $created_attribute = [
        'user' => 'created_by',
        'date' => 'created_at'
    ];

    /**
     * @var array
     */
    public $updated_attribute = [
        'user' => 'updated_by',
        'date' => 'updated_at'
    ];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $blameableAttributes = [];

        if ($this->hasAttribute($this->created_attribute['user'])) {
            $blameableAttributes[BaseActiveRecord::EVENT_BEFORE_INSERT] = $this->created_attribute['user'];
        }
        if ($this->hasAttribute($this->updated_attribute['user'])) {
            $blameableAttributes[BaseActiveRecord::EVENT_BEFORE_UPDATE] = $this->updated_attribute['user'];
        }

        $timestampAttributes = [];
        if ($this->hasAttribute($this->created_attribute['date'])) {
            $timestampAttributes[BaseActiveRecord::EVENT_BEFORE_INSERT] = $this->created_attribute['date'];
        }
        if ($this->hasAttribute($this->updated_attribute['date'])) {
            $timestampAttributes[BaseActiveRecord::EVENT_BEFORE_UPDATE] = $this->updated_attribute['date'];
        }

        $behaviors = [];

        if (!empty($blameableAttributes)) {
            $behaviors['BlameableBehavior'] = [
                'class'        => BlameableBehavior::class,
                'attributes'   => $blameableAttributes,
                'defaultValue' => User::GUEST_ID
            ];
        }

        if (!empty($timestampAttributes)) {
            $behaviors['TimestampBehavior'] = [
                'class'      => TimestampBehavior::class,
                'attributes' => $timestampAttributes,
            ];
        }

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [];
        if ($this->hasAttribute($this->created_attribute['date'])) {
            $rules[] = $this->created_attribute['date'];
        }

        if ($this->hasAttribute($this->created_attribute['user'])) {
            $rules[] = $this->created_attribute['user'];
        }

        if ($this->hasAttribute($this->updated_attribute['date'])) {
            $rules[] = $this->updated_attribute['date'];
        }

        if ($this->hasAttribute($this->updated_attribute['user'])) {
            $rules[] = $this->updated_attribute['user'];
        }

        if ($this->hasAttribute('status')) {
            $rules[] = 'status';
        }

        if (!empty($rules)) {
            $rules = [[$rules, 'integer']];
        }

        return ArrayHelper::merge(parent::rules(), $rules);
    }

    /**
     * Базовые значения labels
     *
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [];
        $labels['id'] = Yii::t('app', 'ID');
        $labels['status'] = Yii::t('app', 'Статус');

        if ($this->hasAttribute($this->created_attribute['user'])) {
            $labels[$this->created_attribute['date']] = Yii::t('app', 'Дата создания');
            $labels[$this->created_attribute['user']] = Yii::t('app', 'Создан');
        }

        if ($this->hasAttribute($this->updated_attribute['date'])) {
            $labels[$this->updated_attribute['date']] = Yii::t('app', 'Дата обновления');
        }

        if ($this->hasAttribute($this->updated_attribute['user'])) {
            $labels[$this->updated_attribute['user']] = Yii::t('app', 'Обновлен');
        }

        return $labels;
    }

    /**
     * Проверить является ли текущий пользователь владельцем этой записи
     *
     * @return bool|null
     */
    public function isOwner()
    {
        if ($this->hasAttribute($this->created_attribute['user'])) {

            if (!Yii::$app->user->isGuest) {
                return Yii::$app->user->id === $this->getAttribute($this->created_attribute['user']);
            } else {
                return false;
            }
        }

        return null;
    }

    /**
     * Получить логин создавшего запись
     * @return mixed|null
     */
    public function getCreatedById()
    {
        if ($this->hasAttribute($this->created_attribute['user'])) {
            return $this->{$this->created_attribute['user']};
        }

        return null;
    }

    /**
     * Получить дату создания объекта
     * @return mixed|null
     */
    public function getCreated()
    {
        if ($this->hasAttribute($this->created_attribute['date'])) {
            return $this->{$this->created_attribute['date']};
        }

        return null;
    }

    /**
     * Получить логин обновившего запись
     * @return mixed|null
     */
    public function getUpdatedById()
    {
        if ($this->hasAttribute($this->updated_attribute['user'])) {
            return $this->{$this->updated_attribute['user']};
        }

        return null;
    }

    /**
     * Получить время обновления записи
     * @return mixed|null
     */
    public function getUpdated()
    {
        if ($this->hasAttribute($this->updated_attribute['date'])) {
            return $this->{$this->updated_attribute['date']};
        }

        return null;
    }

    /**
     * Время последнего обновления
     * @return mixed|null
     */
    public function getLastChange()
    {
        return $this->getUpdated() ?: $this->getCreated();
    }

    /**
     * Получить алиас таблицы для выполнения запросов
     * @return null|string
     */
    public static function getAlias()
    {
        static $alias = null;
        if (!$alias) {
            $array = preg_split("/[\._]/", static::tableName());
            foreach ((array)$array as $value) {
                $alias .= substr($value, 0, 1);
            }
            $alias = strtolower($alias);
        }

        return $alias;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => $this->updated_attribute['user']]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => $this->created_attribute['user']]);
    }

    /**
     * Перегружаем данный метод, для того что бы была возможность подменять ActiveQuery динамически
     * @return object|ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        $queryClass = get_called_class() . 'Query';

        if (class_exists($queryClass)) {
            return Yii::createObject($queryClass, [get_called_class()]);
        } else {
            return Yii::createObject(static::$queryClass, [get_called_class()]);
        }
    }

    /**
     * Найти объект или создать новый
     *
     * @param $condition
     * @return object|static
     * @throws \yii\base\InvalidConfigException
     */
    public static function findOrNew($condition = null)
    {
        if ($condition) {
            if ($object = self::findOne($condition)) {
                return $object;
            }
        }

        /** @var BaseModel $object */
        $object = Yii::createObject(self::class);

        // соблюдение типов
        if (!ArrayHelper::isAssociative($condition)) {
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $condition = [$primaryKey[0] => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        $object->setAttributes($condition);

        return $object;
    }

    /**
     * Получить идентификатор текущей записи
     * @return mixed
     */
    public function getId()
    {
        return $this->primaryKey;
    }

    /**
     * Получить представление модели в виде строки
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }
}
