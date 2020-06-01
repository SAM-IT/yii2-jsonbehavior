<?php
declare(strict_types=1);

namespace  SamIT\Yii2\JsonBehavior\Tests;

use SamIT\Yii2\JsonBehavior\JsonBehavior;
use yii\db\ActiveRecord;

class TestModel extends ActiveRecord
{
    private static $db;
    public static function getDb()
    {
        if (!isset(self::$db)) {
            self::$db = $db = new \yii\db\Connection([
                'dsn' => 'sqlite::memory:',
                'schemaCache' => null
            ]);

            $db->createCommand()->createTable('{{%test_model}}', [
                'id' => 'pk',
                'data' => 'binary'
            ])->execute();
        }
        return self::$db;
    }

    public function behaviors()
    {
        return [
            [
                'class' => JsonBehavior::class,
//            'defaultAttribute' => 'test',
                'jsonAttributes' => ['data']
            ]
        ];
    }
}
