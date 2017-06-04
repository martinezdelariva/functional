<?php

/**
 * (c) José Luis Martínez de la Riva <martinezdelariva@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE file
 *  that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Martinezdelariva\Functional;

function curry_side(bool $left, callable $callable, ... $params) {
    $closure = \Closure::fromCallable($callable);
    $numOfCallableParameters = (new \ReflectionFunction($closure))->getNumberOfParameters();
    $numOfCurrentParameters  = count($params);

    if ($numOfCallableParameters <= $numOfCurrentParameters) {
        return $callable(... $left ? $params : array_reverse($params));
    }

    return function (... $inputs) use ($left, $callable, $params) {
        return curry_side($left, $callable, ... array_merge($params, $inputs));
    };
}

function curry(callable $callable, ... $params) {
    return curry_left($callable, ... $params);
}

function curry_left(callable $callable, ... $params) {
    return curry_side(true, $callable, ... $params);
}

function curry_right(callable $callable, ... $params) {
    return curry_side(false, $callable, ... $params);
}
