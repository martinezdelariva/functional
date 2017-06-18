<?php
/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types=1);

namespace Martinezdelariva\Functional;

function fold(callable $function = null, $init = null, array $items = null) {
    $foldMatching = function (callable $function = null, $init = null, array $items = null) {
         return array_reduce($items, function ($carry, $item) use ($function) {
             $function = curry($function);
             $function = $function($item);

             return is_callable($function) ? $function($carry) : $function;
         }, $init);
    };

    return curry($foldMatching, ...func_get_args());
}
