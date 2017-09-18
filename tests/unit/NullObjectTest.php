<?php

use SamIT\Yii2\Components\NullObject;

class NullObjectTest extends \PHPUnit\Framework\TestCase
{

    public function testIsset()
    {
        $object = new NullObject();
        $this->assertFalse(isset($object['abc']));
    }

    public function testOffsetGet()
    {
        $this->expectException(BadMethodCallException::class);
        $object = new NullObject();
        $object['abc'];
    }

    public function testOffsetSet()
    {
        $this->expectException(BadMethodCallException::class);
        $object = new NullObject();
        $object['abc'] = 5;
    }

    public function testOffsetUnSet()
    {
        $this->expectException(BadMethodCallException::class);
        $object = new NullObject();
        unset($object['abc']);
    }
}