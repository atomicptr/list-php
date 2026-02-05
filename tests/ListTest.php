<?php declare(strict_types=1);

use function List\append;
use function List\cons;
use function List\drop;
use function List\dropWhile;
use function List\every;
use function List\filter;
use function List\find;
use function List\find_index;
use function List\first;
use function List\flat_map;
use function List\flatten;
use function List\for_all;
use function List\group_by;
use function List\hd;
use function List\init;
use function List\last;
use function List\map;
use function List\partition;
use function List\second;
use function List\slice;
use function List\some;
use function List\sort_list;
use function List\sort_unique;
use function List\take;
use function List\take_while;
use function List\third;
use function List\tl;
use function List\try_nth;
use function List\unique;

test('map', function () {
    expect(map(fn(int $val, int $index) => $val + $index, [5, 4, 3, 2, 1]))->toBe([5, 5, 5, 5, 5]);
});

test('filter', function () {
    expect(filter(fn(int $val) => $val % 2 === 0, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]))->toBe([2, 4, 6, 8, 10]);
});

test('partition', function () {
    list($even, $odd) = partition(fn(int $num) => $num % 2 === 0, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    expect($even)->toBe([2, 4, 6, 8, 10]);
    expect($odd)->toBe([1, 3, 5, 7, 9]);
});

test('find', function () {
    $res = find(fn(int $num) => $num % 2 === 0, [1, 3, 5, 6, 10]);
    expect($res)->toBe(6);

    // @phpstan-ignore-next-line
    $res = find(fn(int $num) => ($num % 2) === 0, [1, 3, 5, 7]);
    expect($res)->toBeNull();
});

test('find_index', function () {
    $res = find_index(fn(int $num) => $num % 2 === 0, [1, 3, 5, 6, 10]);
    expect($res)->toBe(3);

    // @phpstan-ignore-next-line
    $res = find_index(fn(int $num) => $num % 2 === 0, [1, 3, 5, 7]);
    expect($res)->toBeNull();
});

test('for_all', function () {
    $vars = [1, 2, 3, 4];
    for_all(function (int $num, int $index) use ($vars) {
        expect($num)->toBe($vars[$index]);
    }, $vars);
});

test('some', function () {
    expect(some(fn(int $num) => $num % 2 === 0, [1, 3, 5, 7, 10]))->toBeTrue();
});

test('every', function () {
    // @phpstan-ignore-next-line
    expect(every(fn(int $num) => $num % 2 === 0, [2, 4, 6, 8, 10]))->toBeTrue();
});

test('hd', function () {
    expect(hd([1, 2]))->toBe(1);
});

test('tl', function () {
    expect(tl([1, 2]))->toBe([2]);
    expect(tl([1, 2, 3, 4]))->toBe([2, 3, 4]);
    expect(tl([1]))->toBe([]);
    expect(tl([]))->toBe([]);
});

test('first', function () {
    expect(first([1, 2, 3, 4]))->toBe(1);
});

test('second', function () {
    expect(second([1, 2, 3, 4]))->toBe(2);
});

test('third', function () {
    expect(third([1, 2, 3, 4]))->toBe(3);
});

test('last', function () {
    expect(last([1, 2, 3, 4]))->toBe(4);
});

test('init', function () {
    expect(init(fn(int $num) => $num + 1, 3))->toBe([1, 2, 3]);
});

test('append', function () {
    expect(append([1, 2, 3], [4, 5, 6]))->toBe([1, 2, 3, 4, 5, 6]);
});

test('cons', function () {
    $arr = [1, 2, 3, 4];
    expect(cons($arr, 5))->toBe([1, 2, 3, 4, 5]);
    expect($arr)->toBe([1, 2, 3, 4]);
});

test('flatten', function () {
    expect(flatten([[[1, 2], 3], [4, [5]], 6]))->toBe([1, 2, 3, 4, 5, 6]);
});

test('flat_map', function () {
    expect(flat_map(fn(string $s) => explode(' ', $s), ['hello world', 'this is a list with', 'strings']))
        ->toBe(['hello', 'world', 'this', 'is', 'a', 'list', 'with', 'strings']);
});

test('take', function () {
    expect(take([1, 2, 3, 4, 5], 2))->toBe([1, 2]);
    expect(take([1, 2, 3, 4, 5], 10))->toBe([1, 2, 3, 4, 5]);
});

test('take_while', function () {
    expect(take_while(fn(int $num) => $num % 2 === 0, [2, 4, 6, 7, 10]))->toBe([2, 4, 6]);
});

test('drop', function () {
    expect(drop([1, 2, 3, 4, 5], 2))->toBe([3, 4, 5]);
});

test('dropWhile', function () {
    expect(dropWhile(fn(int $num) => $num % 2 === 0, [2, 4, 6, 7, 10]))->toBe([7, 10]);
});

test('slice', function () {
    expect(slice([1, 2, 3, 4, 5], 2, 1))->toBe([3]);
});

test('sort', function () {
    $lst = [5, 100, 4, 3, 2, 1];
    expect(sort_list(fn(int $a, int $b) => $a <=> $b, $lst))->toBe([1, 2, 3, 4, 5, 100]);
    expect($lst)->toBe([5, 100, 4, 3, 2, 1]);
});

test('unique', function () {
    expect(unique([1, 1, 2, 2, 3]))->toBe([1, 2, 3]);
});

test('sort_unique', function () {
    $lst = [100, 1, 1, 0];
    expect(sort_unique(fn(int $a, int $b) => $a <=> $b, $lst))->toBe([0, 1, 100]);
});

test('group_by', function () {
    $result = group_by(fn(int $num) => $num % 2 === 0 ? 'even' : 'odd', [1, 2, 3, 4, 5]);
    expect($result['even'])->toBe([2, 4]);
    expect($result['odd'])->toBe([1, 3, 5]);
});

test('try_nth', function () {
    expect(try_nth([1, 2, 3], 1))->toBe(2);
    expect(try_nth([1, 2, 3], 4))->toBeNull();
});
