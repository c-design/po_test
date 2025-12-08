<?php

declare(strict_types=1);

use yii\db\Migration;

class m251208_110000_apple_eaten_at extends Migration
{
    private const string TABLE_NAME = 'apples';

    public function safeUp(): void
    {
        $this->addColumn(self::TABLE_NAME, 'eaten_at',  $this->dateTime()->null());
    }

    public function safeDown(): void
    {
        $this->dropColumn(self::TABLE_NAME, 'eaten_at');
    }
}
