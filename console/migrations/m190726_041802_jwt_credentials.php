<?php

use yii\db\Migration;

/**
 * Class m190726_041802_jwt_credentials
 */
class m190726_041802_jwt_credentials extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('jwt', [
            'id'         => $this->primaryKey(),
            'token'      => $this->string(255)->unique()->notNull()->comment('Ключ'),
            'expired'    => $this->integer()->notNull()->comment('Время жизни ключа'),
            'user_id'    => $this->integer()->notNull()->comment('Пользователь с кем связан токен'),
            'ip2long'    => $this->integer()->notNull()->comment('IP Адрес с которого был создан токен'),
            'created_at' => $this->integer()->notNull()->comment('Когда добавлено'),
            'created_by' => $this->integer()->notNull()->comment('Кем добавлено'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_jwt_created_by',
            'jwt',
            'created_by',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk_jwt_user_id',
            'jwt',
            'user_id',
            'user',
            'id'
        );

        $this->createIndex('jwt_i01', 'jwt', 'expired');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_jwt_created_by', 'jwt');
        $this->dropForeignKey('fk_jwt_user_id', 'jwt');
        $this->dropIndex('jwt_i01', 'jwt');

        $this->dropTable('jwt');

        return true;
    }
}
