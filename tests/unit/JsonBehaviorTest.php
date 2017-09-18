<?php
class TestModel extends \yii\db\ActiveRecord
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
            'class' => \SamIT\Yii2\Behaviors\JsonBehavior::class,
//            'defaultAttribute' => 'test',
            'jsonAttributes' => ['data']
        ]
        ];

    }
}

class JsonBehaviorTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        parent::setUp();
        TestModel::getDb()->createCommand()->insert(TestModel::tableName(), [
            'data' => json_encode([
                'a' => 'b',
                'b' => ['c' => 'd']
            ])
        ])->execute();
    }


//    public function testCreate()
//    {
//        $model = new TestModel();
//    }

    public function testFind()
    {
        $model = TestModel::find()->one();

        $this->assertInstanceOf(TestModel::class, $model);
        $this->assertTrue(isset($model->data['a']));
        $this->assertFalse(isset($model->data['c']));
        $this->assertEquals('b', $model->data['a']);

        $this->assertTrue(isset($model->data['b']['c']));
        $this->assertFalse(isset($model->data['b']['d']));
        $this->assertEquals('d', $model->data['b']['c']);

    }

    public function testDefaultAttribute()
    {
        $model = TestModel::find()->one();

        $this->assertInstanceOf(TestModel::class, $model);

        $this->assertTrue(isset($model->a));
        $this->assertEquals('b', $model->a);

        $this->assertTrue(isset($model->b['c']));
        $this->assertEquals('d', $model->b['c']);
    }

    public function testSetArray()
    {
        $model = new TestModel();

        $model->data = ['a' => 'x'];

        $this->assertTrue(isset($model->a));
        $this->assertTrue($model->save());
        $this->assertTrue(isset($model->data['a']));
    }

}