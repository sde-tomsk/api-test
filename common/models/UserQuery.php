<?php

namespace common\models;

use common\models\base\BaseQuery;

/**
 * User model
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class UserQuery extends BaseQuery
{
    /**
     * Заготовка для поиска пользователя по указанной почте
     *
     * @param string $email Имя почтового адреса
     * @return self
     */
    public function prepareEmail($email)
    {
        $a = $this->alias;

        return $this
            ->alias($a)
            ->innerJoin(['ue' => UserEmail::tableName()], $a . '.id = ue.user_id')
            ->innerJoin(['e' => Email::tableName()], 'e.id = ue.email_id')
            ->andWhere([
                'lower(e.value)' => strtolower($email)
            ]);
    }
}
