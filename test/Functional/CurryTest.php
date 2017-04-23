<?php
/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Martinezdelariva\Tests\Functional;

use PHPUnit\Framework\TestCase;
use function Martinezdelariva\Functional\curry;

class CurryTest extends TestCase
{
    /**
     * @dataProvider add2Provider
     *
     * @param callable $callable
     */
    public function test_providing_first_param(callable $callable)
    {
        $add1 = curry($callable, 1);

        $this->assertEquals(3, $add1(2));
    }

    /**
     * @dataProvider add2Provider
     *
     * @param callable $callable
     */
    public function test_providing_no_params(callable $callable)
    {
        $add  = curry($callable);
        $add1 = $add(1);

        $this->assertEquals(3, $add1(2));
    }

    /**
     * @dataProvider add2Provider
     *
     * @param callable $callable
     */
    public function test_providing_all_params(callable $callable)
    {
        $result = curry($callable, 1, 2);

        $this->assertEquals(3, $result);
    }

    /**
     * @dataProvider add3Provider
     *
     * @param callable $callable
     */
    public function test_provide_2_params_to_3_arity_function(callable $callable)
    {
        $add3 = curry($callable, 1, 2);

        $this->assertEquals(6, $add3(3));
    }

    /**
     * @dataProvider add3Provider
     *
     * @param callable $callable
     */
    public function test_provide_1_param_to_3_arity_function_and_rest_params_sequentially(callable $callable)
    {
        $add1 = curry($callable, 1);
        $add3 = $add1(2);

        $this->assertEquals(6, $add3(3));
    }

    /**
     * @dataProvider add3Provider
     *
     * @param callable $callable
     */
    public function test_provide_1_param_to_3_arity_function_and_rest_params_at_once(callable $callable)
    {
        $add1 = curry($callable, 1);

        $this->assertEquals(6, $add1(2, 3));
    }

    /**
     * @dataProvider minusProvider
     *
     * @param callable $callable
     */
    public function test_that_order_of_params_matters(callable $callable)
    {
        $minus = curry($callable, 2);

        $this->assertEquals(-1, $minus(3));
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
