<?php

use PHPUnit\Framework\TestCase;
use Lasso\Enumerable\Enumerable;

class TopTen
{
    use Enumerable;

    protected function __each()
    {
        for ($i = 10; $i > 0; $i--) {
            yield $i;
        }
    }
}

class EnumerableTest extends TestCase
{
    private static $topTen;
    private static $largerThanFive;
    private static $dividableByThree;

    public static function setupBeforeClass()
    {
        self::$topTen = new TopTen();
        self::$largerThanFive = function($elem) { return $elem > 5; };
        self::$dividableByThree = function($elem) { return $elem % 3 === 0; };
    }

    public function testCountWithCallback() {
        $this->assertEquals(5, self::$topTen->count(self::$largerThanFive));
    }

    public function testCountWithoutCallback()
    {
        $this->assertEquals(10, self::$topTen->count());
    }

    public function testEach()
    {
        $printer = function($elem) { printf("%s\n", $elem); };
        ob_start();
        self::$topTen->each($printer);
        $output = ob_get_clean();
        $this->assertEquals("10\n9\n8\n7\n6\n5\n4\n3\n2\n1\n", $output);
    }

    public function testFind()
    {
        $this->assertEquals(9, self::$topTen->find(self::$dividableByThree));
    }
}
