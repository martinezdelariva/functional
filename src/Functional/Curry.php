<?php

/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Martinezdelariva\Functional;

/**
 * @param callable $callable
 * @param array    ...$params
 *
 * @return \Closure|mixed
 */
function curry(callable $callable, ... $params)
{
    $closure = \Closure::fromCallable($callable);
    $numOfCallableParameters = (new \ReflectionFunction($closure))->getNumberOfParameters();
    $numOfCurrentParameters  = count($params);

    if ($numOfCallableParameters <= $numOfCurrentParameters) {
        return $callable(... $params);
    }

    return function (... $inputs) use ($callable, $params) {
        return curry($callable, ... array_merge($params, $inputs));
    };
}
