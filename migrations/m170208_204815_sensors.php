<?php

use yii\db\Migration;

class m170208_204815_sensors extends Migration
{
    public function up()
    {
        $this->createTable('sensors', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'unit' => $this->string(10),
            'last_value' => $this->decimal(20,4),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->createTable('sensors_data', [
            'id' => $this->primaryKey(),
            'sensor_id' => $this->integer()->notNull(),
            'value' => $this->decimal(20,4),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->addForeignKey('sensors_data_sensor_id_fk', 'sensors_data', ['sensor_id'], 'sensors', ['id'], 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('sensors_data');
        $this->dropTable('sensors');
    }
}
