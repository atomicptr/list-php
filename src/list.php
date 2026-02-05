<?php declare(strict_types=1);

namespace List;

/**
 * Applies the function $fn to every element of $list and builds a new
 * list with the results returned by $fn.
 *
 * Same as array_map
 *
 * @template T
 * @template U
 * @param callable(T $elem, int $index): U $fn
 * @param list<T> $list
 * @return list<U>
 */
function map(callable $fn, array $list): array
{
    return array_map($fn, $list, array_keys($list));
}

/**
 * Applies the function $fn to every element of $list and builds a new
 * list with the elements of $list where $fn returned true.
 *
 * Same as array_filter
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @param list<T> $list
 * @return list<T>
 */
function filter(callable $fn, array $list): array
{
    return array_values(array_filter($list, $fn, ARRAY_FILTER_USE_BOTH));
}

/**
 * Partitions the input list into two arrays based on the given predicate function.
 *
 * @template T
 * @param callable(T $value, int $key): bool $fn
 * @param list<T> $list
 * @return array{0: list<T>, 1: list<T>} [matches, nonMatches]
 */
function partition(callable $fn, array $list): array
{
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
}

/**
 * Iterates over $list until one element applied to $fn returns true and returns that element.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @param list<T> $list
 * @return T|null
 */
function find(callable $fn, array $list): mixed
{
    foreach ($list as $key => $value) {
        if ($fn($value, $key)) {
            return $value;
        }
    }

    return null;
}

/**
 * Iterates over $list until one element applied to $fn returns true and returns the index.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @param list<T> $list
 * @return int|null
 */
function find_index(callable $fn, array $list): mixed
{
    foreach ($list as $key => $value) {
        if ($fn($value, $key)) {
            return $key;
        }
    }
    return null;
}

/**
 * Applies the function $fn to every element of $list.
 *
 * @template T
 * @param callable(T $elem, int $index): void $fn
 * @param list<T> $list
 * @return void
 */
function for_all(callable $fn, array $list): void
{
    foreach ($list as $key => $value) {
        $fn($value, $key);
    }
}

/**
 * Reduces the array to a single value by applying $fn from left to right.
 *
 * @template T
 * @template R
 * @param callable(R $acc, T $curr): R $fn
 * @param list<T> $list
 * @param R $initial
 * @return R
 */
function foldl(callable $fn, array $list, mixed $initial = null): mixed
{
    return array_reduce($list, $fn, $initial);
}

/**
 * Reduces the array to a single value by applying $fn from right to left.
 *
 * @template T
 * @template R
 * @param callable(T $curr, R $acc): R $fn
 * @param list<T> $list
 * @param R $initial
 * @return R
 */
function foldr(callable $fn, array $list, mixed $initial = null): mixed
{
    return array_reduce(array_reverse($list), fn(mixed $acc, mixed $curr) => $fn($curr, $acc), $initial);
}

/**
 * Returns true if at least one element in the list satisfies the predicate $fn.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @param list<T> $list
 * @return bool
 */
function some(callable $fn, array $list): bool
{
    foreach ($list as $key => $value) {
        if ($fn($value, $key))
            return true;
    }
    return false;
}

/**
 * Returns true if all elements in the list satisfy the predicate $fn.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @param list<T> $list
 * @return bool
 */
function every(callable $fn, array $list): bool
{
    foreach ($list as $key => $value) {
        if (!$fn($value, $key))
            return false;
    }
    return true;
}

/**
 * Returns the number of elements in the list.
 *
 * @template T
 * @param list<T> $list
 * @return int
 */
function length(array $list): int
{
    return count($list);
}

/**
 * Is the list empty?
 *
 * @template T
 * @param list<T> $list
 * @return bool
 */
function isEmpty(array $list): bool
{
    return count($list) === 0;
}

/**
 * Returns the first element of the list.
 *
 * @template T
 * @param list<T> $list
 * @return T
 */
function hd(array $list): mixed
{
    if (empty($list)) {
        throw new \InvalidArgumentException('List is empty');
    }

    return $list[0];
}

/**
 * Returns a new list containing all elements except the first.
 *
 * @template T
 * @param list<T> $list
 * @return list<T>
 */
function tl(array $list): array
{
    return array_slice($list, 1);
}

/**
 * Retrieves the element at the specified index in the list.
 *
 * @template T
 * @param list<T> $list The input list
 * @param int $index The index to retrieve
 * @return T The element at the specified index
 * @throws \AssertionError If the index is out of bounds
 */
function nth(array $list, int $index): mixed
{
    if (!array_key_exists($index, $list)) {
        throw new \AssertionError("Index $index out of bounds");
    }

    return $list[$index];
}

/**
 * Retrieves the first element of the list.
 *
 * @template T
 * @param list<T> $list The input list
 * @return T
 */
function first(array $list): mixed
{
    return nth($list, 0);
}

/**
 * Retrieves the second element of the list.
 *
 * @template T
 * @param list<T> $list The input list
 * @return T
 */
function second(array $list): mixed
{
    return nth($list, 1);
}

/**
 * Retrieves the third element of the list.
 *
 * @template T
 * @param list<T> $list The input list
 * @return T
 */
function third(array $list): mixed
{
    return nth($list, 2);
}

/**
 * Retrieves the last element of the list.
 *
 * @template T
 * @param list<T> $list The input list
 * @return T
 */
function last(array $list): mixed
{
    return nth($list, count($list) - 1);
}

/**
 * Sort a list in increasing order according to a comparison function.
 * This version is immutable (returns a new array).
 *
 * @template T
 * @param callable(T $elem1, T $elem2): int $fn
 * @param list<T> $list
 * @return list<T>
 */
function sort_list(callable $fn, array $list): array
{
    $copy = $list;

    usort($copy, $fn);

    return $copy;
}

/**
 * Returns a new list containing the first $num elements of the input list.
 *
 * @template T
 * @param list<T> $list The input list
 * @param int $num The number of elements to take
 * @return list<T> A new list with up to $num elements
 */
function take(array $list, int $num): array
{
    return array_slice($list, 0, $num);
}

/**
 * Returns a new list containing elements from the start until the predicate returns false.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @param list<T> $list
 * @return list<T>
 */
function take_while(callable $fn, array $list): array
{
    $result = [];

    foreach ($list as $key => $value) {
        if (!$fn($value, $key)) {
            break;
        }

        $result[] = $value;
    }
    return $result;
}

/**
 * Returns a new list with the first $num elements removed.
 *
 * @template T
 * @param list<T> $list
 * @param int $num
 * @return list<T>
 */
function drop(array $list, int $num): array
{
    return array_slice($list, $num);
}

/**
 * Returns a new list with elements dropped from the start until the
 * predicate function $fn returns false.
 *
 * @template T
 * @param callable(T $elem, int $index): bool $fn
 * @param list<T> $list
 * @return list<T>
 */
function dropWhile(callable $fn, array $list): array
{
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
}

/**
 * Returns a portion of the list starting at $start with an optional length.
 *
 * @template T
 * @param list<T> $list
 * @param int $start
 * @param ?int $length
 * @return list<T>
 */
function slice(array $list, int $start = 0, ?int $length = null): array
{
    return array_slice($list, $start, $length);
}

/**
 * Removes duplicate values from a list.
 *
 * @template T
 * @param list<T> $list
 * @return list<T>
 */
function unique(array $list): array
{
    $result = [];

    foreach ($list as $value) {
        if (!in_array($value, $result, true)) {
            $result[] = $value;
        }
    }

    return $result;
}

/**
 * Applies the function $fn to every element and builds a flattened list result.
 *
 * @template T
 * @template U
 * @param callable(T $elem, int $index): list<U> $fn
 * @param list<T> $list
 * @return list<U>
 */
function flat_map(callable $fn, array $list): array
{
    $result = [];
    foreach ($list as $key => $value) {
        foreach ($fn($value, $key) as $inner) {
            $result[] = $inner;
        }
    }

    return $result;
}

/**
 * Attempts to retrieve the element at the specified index.
 *
 * @template T
 * @param list<T> $list
 * @param int $index
 * @return T|null
 */
function try_nth(array $list, int $index): mixed
{
    return $list[$index] ?? null;
}

/**
 * Returns a new list with elements in reverse order.
 *
 * @template T
 * @param list<T> $list
 * @return list<T>
 */
function rev(array $list): array
{
    return array_reverse($list);
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
 * @template U
 * @param list<T> $list1
 * @param list<U> $list2
 * @return list<T|U>
 */
function append(array $list1, array $list2): array
{
    return [...$list1, ...$list2];
}

/**
 * Add element to new list
 *
 * @template T
 * @template U
 * @param list<T> $list
 * @param U $value
 * @return list<T|U>
 */
function cons(array $list, mixed $value): array
{
    return append($list, [$value]);
}

/**
 * Flattens a nested array structure.
 *
 * @param list<mixed> $list
 * @return list<mixed>
 */
function flatten(array $list): array
{
    $result = [];

    foreach ($list as $elem) {
        if (is_array($elem)) {
            foreach (flatten(array_values($elem)) as $nested) {
                $result[] = $nested;
            }
        } else {
            $result[] = $elem;
        }
    }

    return $result;
}

/**
 * Sort a list in increasing order and remove duplicates using a comparison function.
 *
 * @template T
 * @param callable(T $elem1, T $elem2): int $fn
 * @param list<T> $list
 * @return list<T>
 */
function sort_unique(callable $fn, array $list): array
{
    if (empty($list)) {
        return [];
    }

    usort($list, $fn);

    $result = [];

    foreach ($list as $current) {
        if (empty($result) || $fn(end($result), $current) !== 0) {
            $result[] = $current;
        }
    }

    return $result;
}

/**
 * Groups elements of an array by the result of a callable function.
 *
 * @template TKey of array-key
 * @template TValue
 * @param callable(TValue): TKey $fn
 * @param list<TValue> $list
 * @return array<TKey, list<TValue>>
 */
function group_by(callable $fn, array $list): array
{
    $groups = [];
    foreach ($list as $elem) {
        $key = $fn($elem);
        $groups[$key][] = $elem;
    }
    return $groups;
}
