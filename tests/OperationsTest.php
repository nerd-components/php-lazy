<?php

namespace Tests;

use Nerd\Lazy\Generators as G;
use Nerd\Lazy\Transformers as O;
use Nerd\Lazy\Reducers as R;

class OperationsTest extends \PHPUnit_Framework_TestCase
{
    private $generator;

    public function setUp()
    {
        $this->generator = G\range(0, 4);
    }

    public function testMap()
    {
        $map = function ($a) {
            return $a + 1;
        };

        $result = O\map($map, $this->generator);

        $this->assertEquals('(1, 2, 3, 4, 5)', R\toString($result));
    }

    public function testFilter()
    {
        $even = function ($a) {
            return $a % 2 == 0;
        };

        $result = O\filter($even, $this->generator);

        $this->assertEquals('(0, 2, 4)', R\toString($result));
    }

    public function testReject()
    {
        $even = function ($a) {
            return $a % 2 == 0;
        };

        $result = O\reject($even, $this->generator);

        $this->assertEquals('(1, 3)', R\toString($result));
    }

    public function testTake()
    {
        $result = O\take(2, $this->generator);

        $this->assertEquals('(0, 1)', R\toString($result));
    }

    public function testSkip()
    {
        $result = O\skip(2, $this->generator);

        $this->assertEquals('(2, 3, 4)', R\toString($result));
    }

    public function testZip()
    {
        $g1 = G\range(0, 4);
        $g2 = G\range(1, 5);

        $result = O\zip($g1, $g2);

        $this->assertEquals([0, 1], $result->current());
        $result->next();
        $this->assertEquals([1, 2], $result->current());
        $result->next();
        $this->assertEquals([2, 3], $result->current());
        $result->next();
        $this->assertEquals([3, 4], $result->current());
        $result->next();
        $this->assertEquals([4, 5], $result->current());
        $result->next();
        $this->assertFalse($result->valid());
    }

    public function testZipWith()
    {
        $g1 = G\range(0, 4);
        $g2 = G\range(1, 5);
        $glue = function ($a, $b) {
            return "($a, $b)";
        };

        $result = O\zipWith($glue, $g1, $g2);

        $this->assertEquals('((0, 1), (1, 2), (2, 3), (3, 4), (4, 5))', R\toString($result));
    }
}
