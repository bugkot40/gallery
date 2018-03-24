<?php

use yii\db\Migration;

class m171031_035548_init_migration extends Migration
{
    public function up()
    {
        $this->createTable('gallery-advertising', [
            'id' => 'pk',
            'name_file' => 'varchar(50)',
            'description' => 'text(65535)',
        ]);

        $advertisings = [
            ['name_file' => 'banner_1.png'],
            ['name_file' => 'banner_2.png'],
            ['name_file' => 'banner_3.png'],
        ];
        foreach ($advertisings as $advertising) {
            $this->insert('gallery-advertising', $advertising);
        }

    }

    public function down()
    {
        $this->dropTable('gallery-advertising');
    }
}
