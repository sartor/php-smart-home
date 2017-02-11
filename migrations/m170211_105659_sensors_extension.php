<?php

use yii\db\Migration;

class m170211_105659_sensors_extension extends Migration
{
    public function up()
    {
        $this->addColumn('sensors', 'decimals', $this->smallInteger()->notNull()->defaultValue(1));
        $this->addColumn('sensors', 'icon', $this->string(50));
        $this->addColumn('sensors', 'background', $this->string(50));
        $this->addColumn('sensors', 'active', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn('sensors', 'order', $this->integer()->notNull()->defaultValue(0));

        $this->createIndex('sensors_data_sensor_id_created_at', 'sensors_data', ['sensor_id', 'created_at'], true);
    }

    public function down()
    {
        $this->dropIndex('sensors_data_sensor_id_created_at', 'sensors_data');

        $this->dropColumn('sensors', 'decimals');
        $this->dropColumn('sensors', 'icon');
        $this->dropColumn('sensors', 'background');
        $this->dropColumn('sensors', 'active');
        $this->dropColumn('sensors', 'order');
    }
}
