<?php declare(strict_types=1);

namespace List;

use Closure;
use InvalidArgumentException;

/**
 * Applies the function $fn to every element of the passed in list and builds a new
 * list with the results returned by $fn.
 *
 * Same as array_map
 *
 * @template T
 * @template U
 * @param callable(T $elem, int $index): U $fn
 * @return (Closure(list<T>): list<U>)
 */
function map(callable $fn): Closure
{
    /** @param list<T> $list */
    return fn(array $list) => array_map(
        fn($item, $key) => $fn($item, $key),
        $list,
        array_keys($list),
    );
}

/**
 * Applies the function $fn to every element of the passed in list and builds a new
 * list with the elements of the list where $fn returned true.
 *
 * Same as array_filter
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @return (Closure(list<T>): list<T>)
 */
function filter(callable $fn): Closure
{
    /** @param list<T> $list */
    return fn(array $list) => array_values(array_filter(
        $list,
        fn($item, $key) => $fn($item, $key),
        ARRAY_FILTER_USE_BOTH,
    ));
}

/**
 * Partitions the input list into two arrays based on the given predicate function.
 *
 * @template T
 * @param callable(T $value, int $key): bool $fn
 * @return (Closure(list<T>): array{0: list<T>, 1: list<T>})
 */
function partition(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        $matches = [];
        $nonMatches = [];

        foreach ($list as $key => $value) {
            if ($fn($value, $key)) {
                $matches[] = $value;
            } else {
                $nonMatches[] = $value;
            }
        }

        return [$matches, $nonMatches];
    };
}

/**
 * Iterates over $list until one element applied to $fn returns true and returns that element.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @return (Closure(list<T>): (T|null))
 */
function find(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        foreach ($list as $key => $value) {
            if ($fn($value, $key)) {
                return $value;
            }
        }

        return null;
    };
}

/**
 * Iterates over $list until one element applied to $fn returns true and returns the index.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @return (Closure(list<T>): (int|null))
 */
function find_index(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        foreach (array_values($list) as $key => $value) {
            if ($fn($value, $key)) {
                return $key;
            }
        }

        return null;
    };
}

/**
 * Applies the function $fn to every element of the passed list.
 *
 * @template T
 * @param callable(T $elem, int $index): void $fn
 * @return (Closure(list<T>): void)
 */
function for_all(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        foreach ($list as $key => $value) {
            $fn($value, $key);
        }
    };
}

/**
 * Reduces the array to a single value by applying $fn from left to right.
 *
 * @template T
 * @template R
 * @param callable(R $acc, T $curr): R $fn
 * @param R $initial
 * @return (Closure(list<T>): R)
 */
function fold_left(callable $fn, mixed $initial = null): Closure
{
    /** @param list<T> $list */
    return fn(array $list) => array_reduce(
        $list,
        fn(mixed $acc, mixed $curr) => $fn($acc, $curr),
        $initial,
    );
}

/**
 * Reduces the array to a single value by applying $fn from right to left.
 *
 * @template T
 * @template R
 * @param callable(T $curr, R $acc): R $fn
 * @param R $initial
 * @return (Closure(list<T>): R)
 */
function fold_right(callable $fn, mixed $initial = null): Closure
{
    /** @param list<T> $list */
    return fn(array $list) => array_reduce(
        array_reverse($list),
        fn(mixed $acc, mixed $curr) => $fn($curr, $acc),
        $initial,
    );
}

/**
 * Returns true if at least one element in the list satisfies the predicate $fn.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @return (Closure(list<T>): bool)
 */
function some(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        foreach (array_values($list) as $key => $value) {
            if ($fn($value, $key)) {
                return true;
            }
        }

        return false;
    };
}

/**
 * Returns true if all elements in the list satisfy the predicate $fn.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @return (Closure(list<T>): bool)
 */
function every(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        foreach ($list as $key => $value) {
            if (!$fn($value, $key)) {
                return false;
            }
        }

        return true;
    };
}

/**
 * Returns the number of elements in the list.
 */
function length(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return int
     */
    return fn(array $list) => count($list);
}

/**
 * Is the list empty?
 */
function isEmpty(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return int
     */
    return fn(array $list) => count($list) === 0;
}

/**
 * Returns the first element of the list.
 */
function head(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return T
     * @throws InvalidArgumentException
     */
    return function (array $list) {
        if (empty($list)) {
            throw new InvalidArgumentException('List is empty');
        }

        return $list[0];
    };
}

/**
 * Returns a new list containing all elements except the first.
 */
function tail(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return list<T>
     */
    return fn(array $list) => array_slice($list, 1);
}

/**
 * Retrieves the element at the specified index in the list.
 *
 * @param int $index The index to retrieve
 */
function nth(int $index): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return T
     * @throws InvalidArgumentException If the index is out of bounds
     */
    return function (array $list) use ($index) {
        if (!array_key_exists($index, $list)) {
            throw new InvalidArgumentException("Index $index out of bounds");
        }

        return $list[$index];
    };
}

/**
 * Attempts to retrieve the element at the specified index.
 */
function try_nth(int $index): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return T
     */
    return fn(array $list) => $list[$index] ?? null;
}

/**
 * Retrieves the first element of the list.
 */
function first(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return T
     */
    return fn(array $list) => nth(0)($list);
}

/**
 * Retrieves the second element of the list.
 */
function second(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return T
     */
    return fn(array $list) => nth(1)($list);
}

/**
 * Retrieves the third element of the list.
 */
function third(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return T
     */
    return fn(array $list) => nth(2)($list);
}

/**
 * Retrieves the last element of the list.
 */
function last(): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return T
     */
    return fn(array $list) => nth(count($list) - 1)($list);
}

/**
 * Sort a list in increasing order according to a comparison function.
 * This version is immutable (returns a new array).
 *
 * @template T
 * @param callable(T $elem1, T $elem2): int $fn
 * @return (Closure(list<T>): list<T>)
 */
function sort_list(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        /** @var list<T> */
        $copy = array_values($list);

        usort($copy, $fn);

        return $copy;
    };
}

/**
 * Sort a list in increasing order and remove duplicates using a comparison function.
 *
 * @template T
 * @param callable(T $elem1, T $elem2): int $fn
 * @return (Closure(list<T>): list<T>)
 */
function sort_unique(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        if (empty($list)) {
            return [];
        }

        /** @var list<T> */
        $copy = array_values($list);

        usort($copy, $fn);

        $result = [];

        foreach ($copy as $current) {
            if (empty($result) || $fn(end($result), $current) !== 0) {
                $result[] = $current;
            }
        }

        return $result;
    };
}

/**
 * Returns a new list containing the first $num elements of the input list.
 */
function take(int $num): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return list<T>
     */
    return fn(array $list) => array_slice($list, 0, $num);
}

/**
 * Returns a new list containing elements from the start until the predicate returns false.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @return (Closure(list<T>): list<T>)
 */
function take_while(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        $result = [];

        foreach ($list as $key => $value) {
            if (!$fn($value, $key)) {
                break;
            }

            $result[] = $value;
        }
        return $result;
    };
}

/**
 * Returns a new list with the first $num elements removed.
 */
function drop(int $num): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return list<T>
     */
    return fn(array $list) => array_slice($list, $num);
}

/**
 * Returns a new list with elements dropped from the start until the
 * predicate function $fn returns false.
 */
function drop_while(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        $dropped = false;
        $result = [];

        foreach ($list as $key => $value) {
            if (!$dropped && !$fn($value, $key)) {
                $dropped = true;
            }
            if ($dropped) {
                $result[] = $value;
            }
        }

        return $result;
    };
}

/**
 * Returns a portion of the list starting at $start with an optional length.
 */
function slice(int $start = 0, ?int $length = null): Closure
{
    /**
     * @template T
     * @param list<T> $list
     * @return list<T>
     */
    return fn(array $list) => array_slice($list, $start, $length);
}

/**
 * Removes duplicate values from a list.
 */
function unique(): Closure
{
    /**
     * @param list<T> $list
     * @return (Closure(list<T>): list<T>)
     */
    return function (array $list) {
        $result = [];

        foreach ($list as $value) {
            if (!in_array($value, $result, true)) {
                $result[] = $value;
            }
        }

        return $result;
    };
}

/**
 * Applies the function $fn to every element and builds a flattened list result.
 *
 * @template T
 * @template U
 * @param callable(T $elem, int $index): list<U> $fn
 * @return (Closure(list<T>): list<U>)
 */
function flat_map(callable $fn): Closure
{
    /** @param list<T> $list */
    return function (array $list) use ($fn) {
        $result = [];

        foreach ($list as $key => $value) {
            foreach ($fn($value, $key) as $inner) {
                $result[] = $inner;
            }
        }

        return $result;
    };
}

/**
 * Returns a new list with elements in reverse order.
 */
function reverse(): Closure
{
    /**
     * @param list<T> $list
     * @return list<T>
     */
    return fn(array $list) => array_reverse($list);
}

/**
 * Creates a new list of given length using the provided function.
 *
 * @template T
 * @param callable(int $index): T $fn
 * @param int $length
 * @return list<T>
 */
function init(callable $fn, int $length): array
{
    $list = [];

    for ($i = 0; $i < $length; $i++) {
        $list[] = $fn($i);
    }

    return $list;
}

/**
 * Concatenates two lists.
 *
 * @template T
 * @param list<T> $otherList
 */
function append(array $otherList): Closure
{
    /**
     * @template U
     * @param list<T> $list
     * @return list<T|U>
     */
    return fn(array $list) => [...$list, ...$otherList];
}

/**
 * Add element to new list
 *
 * @template U
 * @param U $value
 */
function cons(mixed $value): Closure
{
    /**
     * @param list<T> $list
     * @return list<T|U>
     */
    return fn(array $list) => [...$list, $value];
}

/**
 * Flattens a nested array structure.
 */
function flatten(): Closure
{
    /**
     * @param list<mixed> $list
     * @return list<mixed>
     */
    return function (array $list) {
        $result = [];

        foreach ($list as $elem) {
            if (is_array($elem)) {
                /** @var list<mixed> */
                $arr = flatten()(array_values($elem));

                foreach ($arr as $nested) {
                    $result[] = $nested;
                }

                continue;
            }

            $result[] = $elem;
        }

        return $result;
    };
}

/**
 * Groups elements of an array by the result of a callable function.
 *
 * @template TKey of array-key
 * @template TValue
 * @param callable(TValue): TKey $fn
 * @return (Closure(list<TValue>): array<TKey, list<TValue>>)
 */
function group_by(callable $fn): Closure
{
    return function (array $list) use ($fn) {
        $groups = [];

        foreach ($list as $elem) {
            $key = $fn($elem);
            $groups[$key][] = $elem;
        }

        return $groups;
    };
}
