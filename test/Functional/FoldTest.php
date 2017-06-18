<?php

/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types=1);

namespace Functional;

use const Martinezdelariva\Functional\_;
use function Martinezdelariva\Functional\{fold, match};
use PHPUnit\Framework\TestCase;

class FoldTest extends TestCase
{
    /**
     * @dataProvider foldProvider
     *
     * @param callable $function
     * @param mixed $init
     * @param array $items
     * @param mixed $expected
     */
    public function test_behaviour($function, $init, $items, $expected)
    {
        $this->assertEquals($expected, fold($function, $init, $items));
    }

    /**
     * @dataProvider foldProvider
     *
     * @param callable $function
     * @param mixed $init
     * @param array $items
     * @param mixed $expected
     */
    public function test_is_curried($function, $init, $items, $expected)
    {
        $this->assertEquals($expected, fold($function)($init)($items));
    }

    public function foldProvider()
    {
        return [
            // simple sum
            [
                'function' => function ($item, $carry) {
                                return $carry + $item;
                              },
                'init'     => 3,
                'items'    => [1, 2, 3],
                'expected' => 9,
            ],
            // order of param matters
            [
                'function' => function ($item, $carry) {
                                return $carry.$item;
                              },
                'init'     => '',
                'items'    => ['a', 'b', 'c'],
                'expected' => 'abc',
            ],
            // matching non callables
            [
                'function' => match([
                                1 => 2,
                                2 => 3,
                                3 => 4,
                            ]),
                'init'     => '',
                'items'    => [1, 2, 3],
                'expected' => 4,
            ],
            // matching callables
            [
                'function' => match([
                                1 =>    function ($item, $carry) {
                                            return $carry + $item;
                                        },
                                2 =>    function ($item, $carry) {
                                            return $carry - $item;
                                        },
                                3 =>    function ($item, $carry) {
                                            return $carry * $item;
                                        },
                                _ =>    function ($item, $carry) {
                                            return $carry % $item;
                                        },
                            ]),
                'init'     => 10,
                'items'    => [1, 2, 3, 4],
                'expected' => 3,
            ],
        ];
    }
}
