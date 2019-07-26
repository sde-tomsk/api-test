<?php

use yii\db\Migration;

/**
 * Class m190726_040655_user_credentials
 */
class m190726_040655_user_credentials extends Migration
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

        $this->createTable('email', [
            'id'         => $this->primaryKey(),
            'value'      => $this->string(255)->unique()->notNull()->comment('Название почтового адреса'),
            'status'     => $this->smallInteger()->notNull()->defaultValue(1)->comment('Статус записи'),
            'created_at' => $this->integer()->notNull()->comment('Когда добавлено'),
            'created_by' => $this->integer()->notNull()->comment('Кем добавлено'),
            'updated_at' => $this->integer()->comment('Кем обновлено'),
            'updated_by' => $this->integer()->comment('Когда обновлено'),
        ], $tableOptions);

        $this->createIndex('email_i01', 'email', 'status');

        $this->addForeignKey(
            'fk_email_created_by',
            'email',
            'created_by',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk_email_updated_by',
            'email',
            'updated_by',
            'user',
            'id'
        );

        $this->createTable('password_hash', [
            'id'            => $this->primaryKey(),
            'password_hash' => $this->string(255)->notNull()->comment('Шифрованный пароль'),
        ], $tableOptions);

        $this->createIndex('password_hash_i01', 'password_hash', 'password_hash', true);

        $this->createTable('user_email', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer()->notNull()->comment('Пользователь'),
            'email_id'   => $this->integer()->notNull()->comment('Почтовый адрес пользователя'),
            'created_at' => $this->integer()->notNull()->comment('Когда добавлено'),
            'created_by' => $this->integer()->notNull()->comment('Кем добавлено'),
            'updated_at' => $this->integer()->comment('Кем обновлено'),
            'updated_by' => $this->integer()->comment('Когда обновлено'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_ue_created_by',
            'user_email',
            'created_by',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk_ue_updated_by',
            'user_email',
            'updated_by',
            'user',
            'id'
        );

        $this->createIndex('email_i01', 'user_email', 'user_id');
        $this->createIndex('email_i02', 'user_email', 'email_id');

        $this->createIndex('email_u01', 'user_email', [
            'user_id',
            'email_id'
        ], true);
        
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_email_created_by', 'email');
        $this->dropForeignKey('fk_email_updated_by', 'email');

        $this->dropForeignKey('fk_ue_updated_by', 'user_email');
        $this->dropForeignKey('fk_ue_created_by', 'user_email');

        $this->dropIndex('password_hash_i01', 'password_hash');

        $this->dropIndex('email_i01', 'email');

        $this->dropIndex('email_u01', 'user_email');

        $this->dropTable('user_email');

        $this->dropTable('email');

        $this->dropTable('password_hash');

        return true;
    }
}
