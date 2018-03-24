<?php

use yii\db\Migration;

class m171031_035502_init_migration extends Migration
{
    public function up()
    {
        $this->addColumn('gallery-direction', 'base_name_file', $this->string(50)->unique());
    }

    public function down()
    {
        $this->dropColumn('gallery-direction', 'base_name_file');
    }
}
