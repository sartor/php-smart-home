<?php

use yii\db\Migration;

class m170205_205644_messages extends Migration
{
    public function up()
    {
        $this->createTable('messages', [
            'id' => $this->primaryKey(),
            'email' => $this->string(200)->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp(),
        ]);
    }

    public function down()
    {
        $this->dropTable('messages');
    }
}
