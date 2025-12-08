<?php

declare(strict_types=1);

use common\models\User;
use yii\db\Migration;

class m251208_110000_user extends Migration
{
    private const string TABLE_NAME = 'apples';

    public function safeUp(): void
    {
        $user = new User();
        $user->username = 'test';
        $user->email = 'test@dev.ru';
        $user->setPassword('123456789');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->save();
    }

    public function safeDown(): void
    {
        User::deleteAll(['username' => 'test']);
    }
}
