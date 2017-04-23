<?php
/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Martinezdelariva\Functional;

function pipe(callable ...$callables): callable
{
    return function ($value) use ($callables) {
        return array_reduce($callables, function ($carry, $item) use ($value) {
            return $item($carry);
        }, $value);
    };
}
