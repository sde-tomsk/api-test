<?php

namespace common\models;

use common\behaviors\NextIdBehavior;
use common\behaviors\UniqueHashBehavior;
use common\models\base\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property string $username Уникальное имя пользователя в системе
 * @property string $auth_key Уникальный ключ авторизации
 *
 * @author Dmitry E. Semenov <sde.tomsk@gmail.com>
 * @copyright Self (c) 2019
 */
class User extends BaseModel implements IdentityInterface
{
    // Идентификатор гостевой учётнов записи
    const GUEST_ID = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['username', 'auth_key'], 'string', 'max' => 32],
            [['username', 'auth_key'], 'unique'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'username' => Yii::t('app', 'Уникальное имя пользователя'),
            'auth_key' => Yii::t('app', 'Ключ авторизации')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'HashBehavior' => [
                'class'         => UniqueHashBehavior::class,
                'hashAttribute' => 'auth_key',
                'hashLength'    => 32
            ],
            'username'     => [
                'class'         => UniqueHashBehavior::class,
                'hashAttribute' => 'username',
                'hashLength'    => 8
            ],
//            'NextIdBehavior' => [
//                'class'       => NextIdBehavior::class,
//                'idAttribute' => 'username',
//                'tableSchema' => 'api-test',
//                'tableName'   => 'user',
//                'value'       => function () {
//                    return 'id' . $this->nextId()';
//                }
//            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = (string)$token->getClaim('uid');

        return static::findOne(['id' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // @TODO - найти более оптимальное решение
        $this->updateAttributes([
            'username' => 'id' . $this->id
        ]);
    }
}
