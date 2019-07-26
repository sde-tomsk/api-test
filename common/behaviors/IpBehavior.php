<?php

namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\web\Request;

/**
 * IpBehavior автоматически заполняет указанные атрибуты с текущим ip адресом
 *
 * use common\behaviors\IpBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'IpBehavior' => [
 *             'class'            => IpBehavior::className(),
 *             'ip2longAttribute' => 'ip2long',
 *         ],
 *     ];
 * }
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class IpBehavior extends AttributeBehavior
{
    /**
     * @var string Атрибут с указанием IP адреса
     */
    public $ip2longAttribute = 'ip2long';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->ip2longAttribute],
            ];
        }
    }

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($value = $this->owner->{$this->ip2longAttribute}) {
            return $value;
        } elseif ($this->value instanceof Expression) {
            return $this->value;
        } elseif ($this->value instanceof \Closure) {
            return call_user_func($this->value, $event);
        } else {
            if (Yii::$app->request instanceof Request) {
                return ip2long(Yii::$app->request->userIP);
            } else {
                return null;
            }
        }
    }

    /**
     * Преобразовать значение к формату IP
     * @return string
     */
    public function toIp()
    {
        if ($this->owner->{$this->ip2longAttribute}) {
            return long2ip($this->owner->{$this->ip2longAttribute});
        } else {
            return null;
        }
    }

    /**
     * Проверить текущий IP адрес
     * @param null|number $ip2long IP адрес в формате числа (для получения числа нужно воспользоваться функцией ip2long)
     * @return bool
     */
    public function checkIp($ip2long = null)
    {
        if (!$ip2long) {
            if (Yii::$app->request instanceof Request) {
                $ip2long = ip2long(Yii::$app->request->userIP);
            } else {
                $ip2long = null;
            }
        }

        return $this->owner->{$this->ip2longAttribute} &&
            $ip2long &&
            $this->owner->{$this->ip2longAttribute} == $ip2long;
    }
}
