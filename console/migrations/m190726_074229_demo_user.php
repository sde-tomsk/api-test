<?php

use yii\db\Migration;

/**
 * Class m190726_074229_demo_user
 */
class m190726_074229_demo_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $f = new \common\forms\SignupForm();
        $f->load([
            'email'    => 'demo@demo.ru',
            'password' => '12345678',
        ], '');

        return $f->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
