<?php

use PHPUnit\Framework\TestCase;
use Lasso\Enumerable\EnumerableArray;

class EnumerableArrayTest extends TestCase
{
    public function testArrayCopy()
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
}
