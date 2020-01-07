<?php

use yii\db\Schema;
use yii\db\Migration;

class m150805_092256_create_tenant_tables extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        /* BEGIN tenant */
        $this->createTable('{{%auth0_tenants}}', [
            'id'             => Schema::TYPE_PK,
            'name'           => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at'     => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'create_user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'updated_at'     => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'update_user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ]);
        /* END tenant */

        /* BEGIN tenant_user */
        $this->createTable('{{%auth0_tenants_users}}', [
            'id'             => Schema::TYPE_PK,
            'tenant_id'      => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'     => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'create_user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'updated_at'     => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'update_user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ]);
        /* END tenant_user */

        /* BEGIN relationship */
        $this->addForeignKey(
            'fk_tenant_tenant_user',
            '{{%auth0_tenants_users}}',
            'tenant_id',
            '{{%auth0_tenants}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk_user_tenant_user',
            '{{%auth0_tenants_users}}',
            'user_id',
            '{{%auth0_users}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        /* END relationship */

        /* BEGIN index */
        $this->createIndex(
            'ix_tenant_name',
            '{{%auth0_tenants}}',
            'name',
            true
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_tenant_tenant_user', '{{%auth0_tenants_users}}');
        $this->dropForeignKey('fk_user_tenant_user', '{{%auth0_tenants_users}}');
        $this->dropTable('{{%auth0_tenants}}');
        $this->dropTable('{{%auth0_tenants_users}}');
    }
}
