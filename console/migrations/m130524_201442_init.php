<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
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

        $this->createTable('{{%user}}', [
            'id'         => $this->primaryKey(),
            'username'   => $this->string(32)->notNull()->unique(),
            'auth_key'   => $this->string(32)->notNull()->unique(),
            'status'     => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull()->comment('Когда добавлено'),
            'created_by' => $this->integer()->notNull()->comment('Кем добавлено'),
            'updated_at' => $this->integer()->comment('Кем обновлено'),
            'updated_by' => $this->integer()->comment('Когда обновлено'),
        ], $tableOptions);

        // Гостевая запись
        $this->insert('{{%user}}', [
            'username'   => 'guest',
            'auth_key'   => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'created_by' => \common\models\User::GUEST_ID
        ]);

        $this->createIndex('user_i01', '{{%user}}', 'status');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('user_i01', '{{%user}}');

        $this->dropTable('{{%user}}');

        return true;
    }
}
