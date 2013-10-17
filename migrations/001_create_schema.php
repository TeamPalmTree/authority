<?php

namespace Fuel\Migrations;

class Create_Schema
{
    public function up()
    {
        // USER

        \DBUtil::create_table('users', array(
                'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
                'group' => array('type' => 'int', 'constraint' => 11, 'default' => 1),
                'username' => array('constraint' => 255, 'type' => 'varchar'),
                'password' => array('constraint' => 255, 'type' => 'varchar'),
                'email' => array('constraint' => 255, 'type' => 'varchar'),
                'login_hash' => array('constraint' => 255, 'type' => 'varchar'),
                'last_login' => array('type' => 'varchar', 'constraint' => 25),
                'created_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
                'updated_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
                'profile_fields' => array('type' => 'text'),
            ), array('id'), false, 'InnoDB', 'utf8_general_ci');

        // USERS PROVIDERS

        \DBUtil::create_table('users_providers', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'parent_id' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
            'provider' => array('type' => 'varchar', 'constraint' => 50),
            'uid' => array('type' => 'varchar', 'constraint' => 255),
            'secret' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
            'access_token' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
            'expires' => array( 'type' => 'int', 'constraint' => 12, 'default' => 0, 'null' => true),
            'refresh_token' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
            'user_id' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
            'created_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
            'updated_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
        ), array('id'));

        \DBUtil::create_index('users_providers', 'parent_id', 'parent_id');

    }

    public function down()
    {
        \DBUtil::drop_table('users_providers');
        \DBUtil::drop_table('users');
    }
}