<?php

use yii\db\Migration;

class m170211_225634_sensors_threshould extends Migration
{
    public function up()
    {
        $this->addColumn('sensors', 'threshold', $this->integer());
        $this->addColumn('sensors', 'sensor', $this->string(100));
    }

    public function down()
    {
        $this->dropColumn('sensors', 'threshold');
        $this->dropColumn('sensors', 'sensor');
    }
}
