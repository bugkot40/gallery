<?php

use yii\db\Migration;

class m171031_035405_init_migration extends Migration
{
    public function up()
    {
        $this->addColumn('gallery-direction', 'name_file', $this->string(50)->unique());
        $this->addColumn('gallery-user', 'name_file', $this->string(100)->unique());
        $this->addColumn('gallery-user', 'base_name_file', $this->string(50));

    }

    public function down()
    {
        $this->dropColumn('gallery-direction', 'name_file');
        $this->dropColumn('gallery-user', 'name_file');
        $this->dropColumn('gallery-user', 'base_name_file');
    }

}
