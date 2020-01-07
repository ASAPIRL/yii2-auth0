<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_094718_create_user_auth_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%auth0_users}}', [
            'id'                   => Schema::TYPE_PK,
            'username'             => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key'             => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash'        => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email'                => Schema::TYPE_STRING . ' NOT NULL',

            'status'     => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        $this->createTable('{{%auth0_auth}}', [
            'id'        => Schema::TYPE_PK,
            'user_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'source'    => Schema::TYPE_STRING . '(255) NOT NULL',
            'source_id' => Schema::TYPE_STRING . '(255) NOT NULL',
        ]);

        /* BEGIN relationship */
        $this->addForeignKey(
            'fk_user_auth',
            '{{%auth0_auth}}',
            'user_id',
            '{{%auth0_users}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        /* END relationship */
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_user_auth', '{{%auth0_auth}}');

        $this->dropTable('{{%auth0_users}}');
        $this->dropTable('{{%auth0_users}}');
    }
}
