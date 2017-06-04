<?php
/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Martinezdelariva\Tests\Functional;

use function Martinezdelariva\Functional\curry;
use function Martinezdelariva\Functional\curry_left;
use function Martinezdelariva\Functional\curry_right;
use PHPUnit\Framework\TestCase;

class CurryTest extends TestCase
{
    /**
     * @dataProvider add2Provider
     *
     * @param callable $callable
     */
    public function test_providing_first_param(callable $callable)
    {
        $this->assertEquals(3, curry($callable, 1)(2));
        $this->assertEquals(3, curry_left($callable, 1)(2));
        $this->assertEquals(3, curry_right($callable, 1)(2));
    }

    /**
     * @dataProvider add2Provider
     *
     * @param callable $callable
     */
    public function test_providing_no_params(callable $callable)
    {
        $this->assertEquals(3, curry($callable)(1)(2));
        $this->assertEquals(3, curry_left($callable)(1)(2));
        $this->assertEquals(3, curry_right($callable)(1)(2));
    }

    /**
     * @dataProvider add2Provider
     *
     * @param callable $callable
     */
    public function test_providing_all_params(callable $callable)
    {
        $this->assertEquals(3, curry($callable, 1, 2));
        $this->assertEquals(3, curry_left($callable, 1, 2));
        $this->assertEquals(3, curry_right($callable, 1, 2));
    }

    /**
     * @dataProvider add3Provider
     *
     * @param callable $callable
     */
    public function test_provide_2_params_to_3_arity_function(callable $callable)
    {
        $this->assertEquals(6, curry($callable, 1, 2)(3));
        $this->assertEquals(6, curry_left($callable, 1, 2)(3));
        $this->assertEquals(6, curry_right($callable, 1, 2)(3));
    }

    /**
     * @dataProvider add3Provider
     *
     * @param callable $callable
     */
    public function test_provide_1_param_to_3_arity_function_and_rest_params_sequentially(callable $callable)
    {
        $this->assertEquals(6, curry($callable, 1)(2)(3));
        $this->assertEquals(6, curry_left($callable, 1)(2)(3));
        $this->assertEquals(6, curry_right($callable, 1)(2)(3));
    }

    /**
     * @dataProvider add3Provider
     *
     * @param callable $callable
     */
    public function test_provide_1_param_to_3_arity_function_and_rest_params_at_once(callable $callable)
    {
        $this->assertEquals(6, curry($callable, 1)(2, 3));
        $this->assertEquals(6, curry_left($callable, 1)(2, 3));
        $this->assertEquals(6, curry_right($callable, 1)(2, 3));
    }

    /**
     * @dataProvider minusProvider
     *
     * @param callable $callable
     */
    public function test_that_order_of_params_matters(callable $callable)
    {
        $this->assertEquals(-1, curry($callable, 2)(3));
        $this->assertEquals(-1, curry_left($callable, 2)(3));
        $this->assertEquals(1, curry_right($callable, 2)(3));
    }

    /**
     * @dataProvider variadicSumProvider
     *
     * @param callable $callable
     */
    public function test_with_variadic_arguments(callable $callable)
    {
        $curried = curry($callable);

        $this->assertEquals(9, $curried(2, 3, 4));
    }

    public function minusProvider()
    {
        return [
            [
                minus::class
            ],
            [
                [Operations::class, 'minus']
            ],
            [
                function ($one, $other) {
                    return minus($one, $other);
                }
            ],
            [
                new class() {
                    public function __invoke($one, $other) {
                        return minus($one, $other);
                    }
                }
            ]
        ];
    }

    public function add2Provider()
    {
        return [
            [
                add2::class
            ],
            [
                [Operations::class, 'add2']
            ],
            [
                function ($one, $other) {
                    return add2($one, $other);
                }
            ],
            [
                new class() {
                    public function __invoke($one, $other) {
                        return add2($one, $other);
                    }
                }
            ]
        ];
    }

    public function add3Provider()
    {
        return [
            [
                add3::class
            ],
            [
                [Operations::class, 'add3']
            ],
            [
                function ($one, $two, $three) {
                    return add3($one, $two, $three);
                }
            ],
            [
                new class() {
                    public function __invoke($one, $two, $three) {
                        return add3($one, $two, $three);
                    }
                }
            ]
        ];
    }

    public function variadicSumProvider()
    {
        return [
            [
                sum::class
            ],
            [
                [Operations::class, 'sum']
            ],
            [
                function (... $sum) {
                    return sum(... $sum);
                }
            ],
            [
                new class() {
                    public function __invoke(... $sum) {
                        return sum(... $sum);
                    }
                }
            ]
        ];
    }
}

class Operations
{
    public static function minus($one, $other)
    {
        return minus($one, $other);
    }

    public static function add2($one, $other)
    {
        return add2($one, $other);
    }

    public static function add3($one, $two, $three)
    {
        return add3($one, $two, $three);
    }

    public static function sum(... $sum)
    {
        return sum(... $sum);
    }
}

function add2($one, $other) {
    return $one + $other;
};

function add3($one, $two, $three) {
    return $one + $two + $three;
};

function minus($one, $other) {
    return $one - $other;
};

function sum(... $sum) {
    return array_sum($sum);
};
