<?php

use yii\db\Migration;

class m171031_035310_init_migration extends Migration
{
    public function up()
    {
        $this->createTable('gallery-user', [
            'id' => $this->primaryKey(),
            'name' => 'varchar(25)',
            'login' => 'varchar(25)',
            'password' => 'varchar(32)',
            'status' => 'varchar(10)',
        ]);
        $password = md5('ulia04082009');
        $users = [
            ['name' => 'Юлия', 'login' => 'ulia', 'password' => $password, 'status' => 'author'],
        ];

        foreach ($users as $user){
            $this->insert('gallery-user', $user);
        }

        $this->createTable('gallery-direction', [
            'id' => 'pk',
            'name' => 'varchar(50)',
        ]);

        $this->createTable('gallery-work', [
            'id' => 'pk',
            'id_direction' => 'int',
            'id_user' => 'int',
            'base_name_file' => 'varchar(50)',
            'name_file' => 'varchar(50)',
            'name_work' => 'varchar(100)',
            'description' => 'varchar(250)',
            'best' => 'bit(1)',
            'load_time' => 'int',
        ]);
        $this->addForeignKey('gallery_work-id_direction-fk', 'gallery-work', 'id_direction', 'gallery-direction', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('gallery_work-id_user-fk', 'gallery-work', 'id_user', 'gallery-user', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('gallery_work-name_file-unique', 'gallery-work', 'name_file', true);
        $this->createIndex('gallery_direction-name-unique', 'gallery-direction', 'name', true);
        $this->createIndex('gallery_user-login-unique', 'gallery-user', 'login', true);
        $this->createIndex('gallery_work-name_work-key', 'gallery-work', 'name_work', false);
        $this->createIndex('gallery_work-description-key', 'gallery-work', 'description', false);
    }

    public function down()
    {
        $this->dropTable('gallery-work');
        $this->dropTable('gallery-user');
        $this->dropTable('gallery-direction');
    }

}
