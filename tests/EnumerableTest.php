<?php

require_once(__DIR__ . '/TopTen.php');

use PHPUnit\Framework\TestCase;
use Lasso3000\Enumerable\EnumerableArray;

class EnumerableTest extends TestCase
{
    private static $topTen;
    private static $largerThanFive;
    private static $largerThanTen;
    private static $dividableByThree;
    private static $double;

    public static function setupBeforeClass()
    {
        self::$topTen = new TopTen();
        self::$largerThanFive = function ($elem) { return $elem > 5; };
        self::$largerThanTen = function ($elem) { return $elem > 10; };
        self::$dividableByThree = function ($elem) { return $elem % 3 === 0; };
        self::$double = function ($elem) { return $elem * 2; };
    }

    public function testAll()
    {
        $this->assertTrue(self::$topTen->all(function ($elem) { return $elem <= 10; }));
        $this->assertFalse(self::$topTen->all(function ($elem) { return $elem <= 5; }));
    }

    public function testAny()
    {
        $this->assertTrue(self::$topTen->any(function ($elem) { return $elem === 5; }));
        $this->assertFalse(self::$topTen->any(function ($elem) { return $elem === 15; }));
    }

    public function testCountWithCallback() {
        $this->assertEquals(5, self::$topTen->count(self::$largerThanFive));
    }

    public function testCountWithoutCallback()
    {
        $this->assertEquals(10, self::$topTen->count());
    }

    public function testDrop()
    {
        $expected = new EnumerableArray([7, 6, 5, 4, 3, 2, 1]);
        $this->assertEquals(
            $expected,
            self::$topTen->drop(3)
        );
        $expected = new EnumerableArray([10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $this->assertEquals(
            $expected,
            self::$topTen->drop(0)
        );
        $expected = new EnumerableArray([]);
        $this->assertEquals(
            $expected,
            self::$topTen->drop(15)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDropNegativeParam()
    {
        self::$topTen->drop(-3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDropStringParam()
    {
        self::$topTen->drop('foo');
    }

    public function testDropWhile()
    {
        $expected = new EnumerableArray([5, 4, 3, 2, 1]);
        $this->assertEquals(
            $expected,
            self::$topTen->dropWhile(self::$largerThanFive)
        );
    }

    public function testDropWhileNoElements()
    {
        $expected = new EnumerableArray();
        $this->assertEquals(
            $expected,
            self::$topTen->dropWhile('is_int')
        );
    }

    public function testEach()
    {
        $printer = function ($elem) { printf("%s\n", $elem); };
        ob_start();
        self::$topTen->each($printer);
        $output = ob_get_clean();
        $this->assertEquals("10\n9\n8\n7\n6\n5\n4\n3\n2\n1\n", $output);
    }

    public function testEachSlice()
    {
        $expected = new EnumerableArray(['10-9-8', '7-6-5', '4-3-2', '1']);
        $this->assertEquals(
            $expected,
            self::$topTen->eachSlice(
                3,
                function(EnumerableArray $slice) {
                    return implode('-', $slice->toArray());
                }
            )
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEachSliceStringParam()
    {
        $callback =
            function(EnumerableArray $slice) {
                return $slice;
            };
        self::$topTen->eachSlice('foo', $callback);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEachSliceNegativeParam()
    {
        $callback =
            function(EnumerableArray $slice) {
                return $slice;
            };
        self::$topTen->eachSlice(-1, $callback);
    }

    public function testFind()
    {
        $this->assertEquals(9, self::$topTen->find(self::$dividableByThree));
        $this->assertEquals(
            null,
            self::$topTen->find(
                function ($elem) { return $elem === 15; }
            )
        );
    }

    public function testFindWithDefault()
    {
        $this->assertEquals(9, self::$topTen->find(self::$dividableByThree, 'not_found'));
        $this->assertEquals(
            'not_found',
            self::$topTen->find(
                function ($elem) { return $elem === 15; },
                'not_found'
            )
        );
    }

    public function testFindAll()
    {
        $expected = new EnumerableArray([10, 9, 8, 7, 6]);
        $this->assertEquals($expected, self::$topTen->findAll(self::$largerThanFive));
        $this->assertEquals(
            new EnumerableArray(),
            self::$topTen->findAll(self::$largerThanTen)
        );
    }

    public function testFindIndexCallable()
    {
        $callback = function ($elem) { return $elem === 7; };
        $this->assertEquals(3, self::$topTen->findIndex($callback));
    }

    public function testFindIndexCallableNotFound()
    {
        $callback = function ($elem) { return $elem === 0; };
        $this->assertEquals(null, self::$topTen->findIndex($callback));
    }

    public function testFindIndexValue()
    {
        $this->assertEquals(3, self::$topTen->findIndex(7));
    }

    public function testFindIndexValueNotFound()
    {
        $this->assertEquals(null, self::$topTen->findIndex(0));
    }

    public function testMap()
    {
        $this->assertEquals(
            new EnumerableArray([20, 18, 16, 14, 12, 10, 8, 6, 4, 2]),
            self::$topTen->map(self::$double)
        );
    }

    public function testMember()
    {
        $this->assertTrue(self::$topTen->member(5));
        $this->assertFalse(self::$topTen->member(15));
    }

    public function testNone()
    {
        $this->assertTrue(self::$topTen->none(self::$largerThanTen));
        $this->assertFalse(self::$topTen->none(self::$largerThanFive));
    }

    public function testOne()
    {
        $exactlySeven = function($elem) { return $elem === 7; };
        $exactlyFourteen = function($elem) { return $elem === 14; };
        $this->assertTrue(self::$topTen->one($exactlySeven));
        $this->assertFalse(self::$topTen->one($exactlyFourteen));
        $this->assertFalse(self::$topTen->one(self::$largerThanFive));
    }

    public function testPartition()
    {
        $expected =
            new EnumerableArray(
                [
                    new EnumerableArray([9, 6, 3]),
                    new EnumerableArray([10, 8, 7, 5, 4, 2, 1])
                ]
            );
        $this->assertEquals($expected, self::$topTen->partition(self::$dividableByThree));
    }

    public function testReduceInteger()
    {
        $this->assertEquals(
            55,
            self::$topTen->reduce(
                function ($memo, $elem) { return $memo += $elem; }, 0
            )
        );
    }

    public function testReduceArray()
    {
        $this->assertEquals(
            [
                'J' => 10, 'I' => 9, 'H' => 8, 'G' => 7, 'F' => 6,
                'E' => 5, 'D' => 4, 'C' => 3, 'B' => 2, 'A' => 1
            ],
            self::$topTen->reduce(
                function ($memo, $elem) {
                    $memo[chr($elem + 64)] = $elem;
                    return $memo;
                }, []
            )
        );
    }

    public function testReject()
    {
        $this->assertEquals(
            new EnumerableArray([10, 8, 7, 5, 4, 2, 1]),
            self::$topTen->reject(self::$dividableByThree)
        );
    }

    public function testReverseEach()
    {
        $printer = function ($elem) { printf("%s\n", $elem); };
        ob_start();
        self::$topTen->reverseEach($printer);
        $output = ob_get_clean();
        $this->assertEquals("1\n2\n3\n4\n5\n6\n7\n8\n9\n10\n", $output);
    }

    public function testSort()
    {
        $unsorted = new EnumerableArray([1, 7, 3, 9, 4, 5, 2, 8, 6, 10]);
        $sortedAscending = new EnumerableArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $sortedDescending = new EnumerableArray([10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);

        $ascendingSorter = function($a, $b) {
            if ($a < $b) { return -1; }
            if ($b < $a) { return 1; }
            return 0;
        };

        $descendingSorter = function($a, $b) {
            if ($a < $b) { return 1; }
            if ($b < $a) { return -1; }
            return 0;
        };

        $this->assertEquals($sortedAscending, $unsorted->sort($ascendingSorter));
        $this->assertEquals($sortedDescending, $unsorted->sort($descendingSorter));
    }

    public function testTake()
    {
        $expected = new EnumerableArray([10, 9, 8]);
        $this->assertEquals(
            $expected,
            self::$topTen->take(3)
        );
        $expected = new EnumerableArray([]);
        $this->assertEquals(
            $expected,
            self::$topTen->take(0)
        );
        $expected = new EnumerableArray([10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $this->assertEquals(
            $expected,
            self::$topTen->take(15)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTakeNegativeParam()
    {
        self::$topTen->take(-3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTakeStringParam()
    {
        self::$topTen->take('foo');
    }

    public function testTakeWhile()
    {
        $expected = new EnumerableArray([10, 9, 8, 7, 6]);
        $this->assertEquals(
            $expected,
            self::$topTen->takeWhile(self::$largerThanFive)
        );
    }

    public function testTakeWhileAllElements()
    {
        $expected = new EnumerableArray([10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $this->assertEquals(
            $expected,
            self::$topTen->takeWhile('is_int')
        );
    }

    public function testChaining()
    {
        $expected = new EnumerableArray([9, 6]);
        $this->assertEquals(
            $expected,
            self::$topTen->findAll(self::$largerThanFive)->findAll(self::$dividableByThree)
        );
    }
}
