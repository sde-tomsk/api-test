<?php

use yii\db\Migration;

/**
 * Class m190726_045145_book_credentials
 */
class m190726_045145_book_credentials extends Migration
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

        $this->createTable('book', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(255)->notNull()->comment('Название книги'),
            'code'       => $this->string(32)->notNull()->unique()->comment('Уникальный код книги'),
            'status'     => $this->smallInteger()->notNull()->defaultValue(1)->comment('Статус записи'),
            'created_at' => $this->integer()->notNull()->comment('Когда добавлено'),
            'created_by' => $this->integer()->notNull()->comment('Кем добавлено'),
            'updated_at' => $this->integer()->comment('Кем обновлено'),
            'updated_by' => $this->integer()->comment('Когда обновлено'),
        ], $tableOptions);

        $this->createIndex('book_i01', 'book', 'status');

        $this->addForeignKey(
            'fk_books_created_by',
            'book',
            'created_by',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk_books_updated_by',
            'book',
            'updated_by',
            'user',
            'id'
        );

        for ($i = 1; $i <= 5; $i++) {
            // Гостевая запись
            $this->insert('book', [
                'name'       => 'Название книги: ' . $i,
                'code'       => 'code_' . $i,
                'created_at' => time(),
                'created_by' => \common\models\User::GUEST_ID
            ]);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_books_updated_by', 'book');
        $this->dropForeignKey('fk_books_created_by', 'book');

        $this->dropIndex('book_i01', 'book');

        $this->dropTable('book');

        return true;
    }
}
