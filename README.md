# Functional

[![Build Status](https://travis-ci.org/martinezdelariva/functional.svg?branch=master)](https://travis-ci.org/martinezdelariva/functional)

Syntax enhancement operators of functional programming:

* Curry
* Pipe
* Pattern Match
* Fold

At the moment there are plans to include [curry](https://wiki.php.net/rfc/currying) and [pipe](https://wiki.php.net/rfc/pipe-operator) as PHP functions.
Meanwhile you can use this library for fun!

Notes:
* `curry` doesn't know if a function with optional params won't be provided. So, `curry` only runs the function curried when all params are given.
* _currying_ vs _partial function application_ [Wikipedia](https://en.wikipedia.org/wiki/Currying#Contrast_with_partial_function_application)

## Installation

Install it using [Composer](https://getcomposer.org/)


    composer require martinezdelariva/functional


## Usage

Importing as a single function:

```php
   namespace Foo;

   use function Martinezdelariva\Functional\pipe;

   class Bar
   {
        public function baz()
        {
            pipe(
                'trim',
                'ucwords'
            )(' string ');
        }
   }
```

Importing namespace:

```php
   namespace Foo;

   use Martinezdelariva\Functional as F;

   class Bar
   {
        public function baz()
        {
            F\pipe(
                'trim',
                'ucwords'
            )(' string ');
        }
   }
```

## Functions

#### curry

```php
function sum(int $one, int $two, int $three) : int {
    return $one + $two + $three;
};

// curry firsts params
$sum3 = curry('sum', 1, 2);
$sum3(3); // output: 6
$sum3(5); // output: 8

// curry whole function
$sum  = curry('sum');
$sum1 = $sum(1);
$sum1(2, 3); // output: 6

// curry with all params given
curry(sum::class, 1, 2, 3); // output: 6
```
#### curry_left

`curry_left` is an alias of `curry`.

#### curry_right

```php
function minus(int $one, int $two) : int {
    return $one - $two;
};

$minus1 = curry_right('minus');
echo $minus1(1)(4); // output: 3
```

#### match

```php
$patterns = [
    'a'              => "It's an 'a'",
    'foo'            => function ($input, $other) {
                            return "$input::$other";
                        },
    'is_int'         => function ($input) {
                            return $input + 1;
                        },
    \stdClass::class => "It's an 'stdClass'",
    _                => "Default"
];

// provinding params
match($patterns, 'a');          // output: "It's an 'a'"

// provinding one by one param due `match` is curried
$match = match($patterns);
$match(5);                      // output: 6
$match(new \stdClass());        // output: "It's an 'stdClass'"
$match('unknown');              // output: "Default"

// provinding param to callables with more params
$match('foo')('bar');           // output: foo::bar
```

#### Pipe

```php
$format = pipe(
    'trim',
    'ucwords',
    function (string $names) : string {
        return implode(', ', explode(' ', $names));
    }
);

$format('  john mary andre'); // output: John, Mary, Andre
```

#### Fold 

```php
// example: using fold to sum integers
$sum = fold(function($item, $carry) {
   return $item + $carry;
}, 0);
$sum([1, 2, 3, 4]); // output 10

// example: dynamically changing the fold function
$match = match([
   'is_string'  =>  function($item, $carry) {
                        return $carry.$item;
                    },
   'is_int'     =>  function($item, $carry) {
                        return "$carry{X}";
                    },
]);

fold($match, '', ['a', 'b', 'c', 2, 'd', 'e']);  // output "abc{X}de"
```
