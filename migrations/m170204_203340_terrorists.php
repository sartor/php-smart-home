<?php

use yii\db\Migration;

class m170204_203340_terrorists extends Migration
{
    public function up()
    {
        $this->createTable('terrorists', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()
        ]);
    }

    public function down()
    {
        $this->dropTable('terrorists');
    }
}
