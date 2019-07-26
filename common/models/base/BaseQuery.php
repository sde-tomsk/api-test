<?php

namespace common\models\base;

use yii\db\ActiveRecord;

/**
 * Базовый класс BaseQuery
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class BaseQuery extends \yii\db\ActiveQuery
{
    /**
     * Получить алиас для построения запроса
     * @return string
     */
    public function getAlias()
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;

        return $class::getAlias();
    }
}