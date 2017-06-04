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
use function Martinezdelariva\Functional\match;

class MatchTest extends TestCase
{
    /**
     * @dataProvider matchingProvider
     * @param mixed $expression
     * @param array $patterns
     * @param mixed $expected
     */
    public function test_matched_patterns($patterns, $expression, $expected)
    {
        $this->assertEquals($expected, match($patterns, $expression));
    }

    public function test_unmatched_patterns_return_input()
    {
        $object = new \stdClass();

        $this->assertEquals($object, match([], $object));
    }

    /**
     * @dataProvider matchingProvider
     * @param mixed $expression
     * @param array $patterns
     * @param mixed $expected
     */
    public function test_is_curried($patterns, $expression, $expected)
    {
        $match = match($patterns);

        $this->assertEquals($expected, $match($expression));
    }

    public function test_matched_callables_with_more_than_one_param_are_curried()
    {
        $pattern = [
            'foo' =>  function ($one, $other) {
                return "$one-$other";
            }
        ];

        $this->assertEquals('foo-bar', match($pattern, 'foo')('bar'));
    }

    public function matchingProvider()
    {
        $patterns = [
            // with int key
            1 =>  function ($input) {
                return 'with int key';
            },
            // with class key
            \stdClass::class => function ($input) {
                return 'with class key';
            },
            // with string key
            'b' => function ($input) {
                return 'with string key';
            },
            // ensure input is pass
            'c' => function ($input) {
                return 'input is pass: ' . $input;
            },
            // with no callback
            'd' => 'with no callback',
            // with callable key
            'is_float' => function($input) {
                return 'with callable key';
            },
            // with parent-child
            TheChild::class => function ($input) {
                return 'with child instance';
            },
            TheParent::class => function ($input) {
                return 'implement parent class!';
            },
            // default
            '_' => function ($input) {
                return 'default';
            }
        ];

        return [
            [$patterns, 1, 'with int key'],
            [$patterns, new \stdClass(1), 'with class key'],
            [$patterns, 'b', 'with string key'],
            [$patterns, 'c', 'input is pass: c'],
            [$patterns, 'd', 'with no callback'],
            [$patterns, 0.5, 'with callable key'],
            [$patterns, new TheChild(), 'with child instance'],
            [$patterns, new OtherChild(), 'implement parent class!'],
            [$patterns, 'unknown', 'default'],
        ];
    }
}

class TheParent {}
class TheChild extends TheParent {}
class OtherChild extends TheParent {}

