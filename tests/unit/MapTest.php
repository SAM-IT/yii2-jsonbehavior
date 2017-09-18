<?php

use SamIT\Yii2\Components\Map;
use SamIT\Yii2\Components\NullObject;

class MapTest extends \PHPUnit\Framework\TestCase
{

    public function testConstructor()
    {
        $this->assertInstanceOf(Map::class, new Map(json_encode(['test' => 'abc'])));
        $this->assertInstanceOf(Map::class, new Map(['test' => 'abc']));
        $this->assertInstanceOf(Map::class, new Map(new Map(['test' => 'abc'])));
        $this->assertInstanceOf(Map::class, new Map(new NullObject()));

    }

    public function testConstructorInvalidJson()
    {
        $this->expectException(DomainException::class);
        new Map('{-');
    }

    public function testConstructorInvalidArgument()
    {
        $this->expectException(DomainException::class);
        new Map(new stdClass());
    }


    public function issetProvider()
    {
        return [
            [['a' => 'b'], 'a', true],
            [['a' => null], 'a', false],
            [['a' => []], 'a', true],
        ];
    }

    /**
     * @dataProvider issetProvider
     */
    public function testIsset($data, $key, $expected)
    {
        $map = new Map($data);
        $this->assertEquals($expected, isset($map[$key]));
    }

    public function testIssetNested()
    {
        $map = new Map([
            'a' => [
                'b' => null,
                'c' => [
                    'd' => 'e'
                ]
            ]
        ]);

        $this->assertFalse(isset($map['a']['b']));
        $this->assertFalse(isset($map['a']['b']['d']));
        $this->assertTrue(isset($map['a']['c']));
        $this->assertTrue(isset($map['a']['c']['d']));

        $this->assertFalse(isset($map['x']['y']['z']));
        $this->assertFalse(isset($map['x']['y']));
    }

    public function testOffsetGet()
    {
        $map = new Map([
            'a' => 'b',
            'b' => null,
            'c' => [
                'd' => 'e'
            ]

        ]);

        $this->assertEquals('b', $map['a']);
        $this->assertEquals(null, $map['b']);
        $this->assertEquals('e', $map['c']['d']);

    }

    public function testOffsetSet()
    {
        $map = new Map([]);

        $map['a']['b'] = 'c';
        $this->assertTrue(isset($map['a']['b']));
        $this->assertEquals('c', $map['a']['b']);
        $map['b'] = [
            'a' => 'c'
        ];
        $this->assertTrue(isset($map['b']['a']));
        $this->assertFalse(isset($map['b']['c']));

        $this->assertEquals('c', $map['a']['b']);


    }

    public function testOffsetUnSet()
    {
        $map = new Map([]);

        $map['a']['b'] = 'c';
        $this->assertTrue(isset($map['a']['b']));
        $this->assertEquals('c', $map['a']['b']);

        unset($map['a']['b']);
        $this->assertFalse(isset($map['a']['b']));
    }

//  @todo: Create random test case generator.
//    public function testRandomTestProvider()
//    {
//        // Construct random array.
//        $arr = [];
//        $maxDepth = 10;
//        var_dump($this->generate($arr));
//        var_dump($arr);
//        die();
//    }
//
//    protected function generate(&$arr, $path = [], int $maxDepth = 10)
//    {
//        if (count($path) == $maxDepth) {
//            return false;
//        }
//        $key = base64_encode(random_bytes(5));
//        $value = base64_encode(random_bytes(5));
//        $currentArray = &$arr;
//        foreach ($path as $pathElement) {
//            $currentArray = &$currentArray[$pathElement];
//        }
//
//        $currentArray[$key] = $value;
//        return true;
//    }
}