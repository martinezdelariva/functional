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
use function Martinezdelariva\Functional\pipe;

class PipeTest extends TestCase
{
    public function test_pipe()
    {
        $pipe = pipe(
            function ($input) {
                return $input . "b";
            },
            function ($input) {
                return $input . "c";
            },
            function ($input) {
                return $input . "d";
            }
        );

        $this->assertEquals('abcd', $pipe('a'));
    }
}
