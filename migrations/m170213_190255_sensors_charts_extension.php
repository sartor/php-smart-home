<?php

use yii\db\Migration;

class m170213_190255_sensors_charts_extension extends Migration
{
    public function up()
    {
        $this->addColumn('sensors', 'trend_limit', $this->integer());
        $this->addColumn('sensors', 'color', $this->string(7)->notNull()->defaultValue('#dd4b39'));
        $this->addColumn('sensors', 'negative_color', $this->string(7)->notNull()->defaultValue('#00c0ef'));
        $this->addColumn('sensors', 'type', $this->smallInteger()->notNull()->defaultValue(1));
        $this->addColumn('sensors', 'chart', $this->string(20)->notNull()->defaultValue('spline'));
    }

    public function down()
    {
        $this->dropColumn('sensors', 'trend_limit');
        $this->dropColumn('sensors', 'color');
        $this->dropColumn('sensors', 'negative_color');
        $this->dropColumn('sensors', 'type');
        $this->dropColumn('sensors', 'chart');
    }
}
