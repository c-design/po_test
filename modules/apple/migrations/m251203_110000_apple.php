<?php

declare(strict_types=1);

use yii\db\Migration;

class m251203_110000_apple extends Migration
{
    private const string TABLE_NAME = 'apples';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->string(36)->notNull()->unique(),
            'color' => $this->text()->notNull(),
            'health' => $this->integer()->notNull()->defaultValue(100)->unsigned()->notNull(),
            'state' => "ENUM('at_tree', 'at_ground', 'dead')",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'fell_at' => $this->dateTime()->null(),
            'dead_at' => $this->dateTime()->null(),
        ]);

        $this->addPrimaryKey('pkey_apple_id',self::TABLE_NAME, 'id');
    }

    public function safeDown(): void
    {
        $this->dropPrimaryKey('pkey_apple_id',self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
