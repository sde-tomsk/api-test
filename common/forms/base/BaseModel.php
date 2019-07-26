<?php

namespace common\forms\base;

use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\base\ModelEvent;

/**
 * Модель для работы с формой
 *
 * Данная модель для работы с пользователем через UI, использует одну или несколько моделей данных.
 * Должна включать в себя логигу обработки моделей данных.
 *
 * Может генерировать исключения.
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
abstract class BaseModel extends Model
{
    const EVENT_BEFORE_SAVE = 'beforeSave';

    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * Использовать транзацию
     * @var bool
     */
    protected $use_transaction = true;

    /**
     * Непосредственное сохранение данных
     * @return mixed
     */
    protected function doSave()
    {
        return false;
    }

    /**
     * Данный метод будет вызван вне транзакции до сохранения данных
     * @return bool
     */
    protected function beforeSave()
    {
        $event = new ModelEvent();
        $this->trigger(self::EVENT_BEFORE_SAVE, $event);

        return $event->isValid;
    }

    /**
     * Данный метод будет вызван вне транзакции после сохранения
     *
     * @param bool $result данные были успешно добавлены или нет
     */
    protected function afterSave($result)
    {
        $this->trigger(self::EVENT_AFTER_SAVE, new Event());
    }

    /**
     * Сохранение введённых данных с проверкой валидатора
     *
     * @param bool $validate Использовать валидацию при сохранении данных
     * @return bool|mixed
     * @throws \Throwable
     */
    public function save($validate = true)
    {
        if ($validate) {
            if (!$this->validate()) {
                return false;
            }
        }

        if ($this->beforeSave()) {

            if ($this->use_transaction) {
                $result = Yii::$app->db->transaction(function () {
                    return $this->doSave();
                });
            } else {
                $result = $this->doSave();
            }

            $this->afterSave($result);

            return $result;
        } else {
            return false;
        }
    }
}