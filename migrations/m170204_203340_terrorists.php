<?php

use yii\db\Migration;

class m170204_203340_terrorists extends Migration
{
    public function up()
    {
        $this->createTable('terrorists', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200)->notNull(),
            'slug' => $this->string(200)->notNull(),
            'department' => $this->string(200),
            'post' => $this->string(200),
            'info' => $this->text(),
            'feedback' => $this->text(),
            'status' => $this->smallInteger(),
            'active' => $this->boolean()->defaultValue('f'),
            'died_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp(),
        ]);

        $this->createIndex('terrorists_slug', 'terrorists', ['slug']);
    }

    public function down()
    {
        $this->dropTable('terrorists');
    }
}
