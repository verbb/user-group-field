<?php
namespace verbb\usergroupfield\migrations;

use verbb\usergroupfield\fields\UserGroupField;

use craft\db\Migration;

class m220719_000000_update_craft2_fieldtype extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp(): bool
    {
        $this->update('{{%fields}}', ['type' => UserGroupField::class], ['type' => 'UsergroupFieldFieldType']);

        return true;
    }

    public function safeDown(): bool
    {
        echo "m220719_000000_update_craft2_fieldtype cannot be reverted.\n";
        return false;
    }
}