<?php
/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Martinezdelariva\Functional;

const _ = 'Martinezdelariva\Functional\default_match';

function match(array $matching = [], $input = null) {
    $match = function (array $matching, $input) {
        $return = function ($callableOrValue) use ($input) {
            return is_callable($callableOrValue) ? curry($callableOrValue)($input) : $callableOrValue;
        };

        foreach ($matching as $pattern => $callable) {
            if (is_object($input) && is_string($pattern) && is_a($input, $pattern)) {
                return $return($callable);
            }

            if (is_scalar($input) && $pattern == $input) {
                return $return($callable);
            }

            if (is_callable($pattern) && $pattern($input)) {
                return $return($callable);
            }
        };

        return (isset($matching[_])) ? $return($matching[_]) : $input;
    };

    return curry($match)(... func_get_args());
}
