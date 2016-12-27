<?php

require_once(__DIR__ . '/TopTen.php');

use PHPUnit\Framework\TestCase;
use Lasso3000\Enumerable\EnumerableArray;

class EnumerableArrayTest extends TestCase
{
    public function testConstruction()
    {
        $enum1 = new EnumerableArray(['one' => 'foo', 'two' => 'bar', 'three' => 'baz']);
        $enum2 = new EnumerableArray(['foo', 'bar', 'baz']);
        $this->assertEquals($enum1, $enum2);
        $this->assertEquals($enum1->toArray(), $enum2->toArray());
    }

    public function testStaticConstruction()
    {
        $enum1 = EnumerableArray::fromArray(['one' => 'foo', 'two' => 'bar', 'three' => 'baz']);
        $enum2 = EnumerableArray::fromArray(['foo', 'bar', 'baz']);
        $this->assertEquals($enum1, $enum2);
        $this->assertEquals($enum1->toArray(), $enum2->toArray());
    }

    public function testCreateFromEnumerable()
    {
        $enum1 = new EnumerableArray([10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $enum2 = EnumerableArray::fromEnumerable(new TopTen());
        $this->assertEquals($enum1, $enum2);
    }

    public function testArrayToArray()
    {
        $enum = new EnumerableArray(['foo', 'bar', 'baz']);

        $this->assertEquals(['foo', 'bar', 'baz'], $enum->toArray());
    }

    public function testGetExistingOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $this->assertEquals('foo', $enum[0]);
       $this->assertEquals('bar', $enum[1]);
       $this->assertEquals('baz', $enum[2]);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testGetNegativeOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $enum[-1];
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testGetToLargeOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $enum[3];
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testGetNonNumericOffset()
    {
        $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $enum['one'];
    }

    public function testSetExistingOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $enum[0] = 'one';
       $enum[1] = 'two';
       $enum[2] = 'three';

       $this->assertEquals('one', $enum[0]);
       $this->assertEquals('two', $enum[1]);
       $this->assertEquals('three', $enum[2]);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testSetNegativeOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $enum[-1] = 'one';
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testSetToLargeOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $enum[3] = 'three';
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testSetNonNumericOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       $enum['one'] = 'three';
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testUnsetExistingOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       unset($enum[0]);
       unset($enum[1]);
       unset($enum[2]);

       $enum[0];
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testUnsetNegativeOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       unset($enum[-1]);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testUnsetToLargeOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       unset($enum[3]);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testUnsetNonNumericOffset()
    {
       $enum = new EnumerableArray(['foo', 'bar', 'baz']);

       unset($enum['one']);
    }
}
