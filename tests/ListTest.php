<?php declare(strict_types=1);

use function List\append;
use function List\cons;
use function List\drop;
use function List\drop_while;
use function List\every;
use function List\filter;
use function List\find;
use function List\find_index;
use function List\first;
use function List\flat_map;
use function List\flatten;
use function List\for_all;
use function List\group_by;
use function List\head;
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
use function List\tail;
use function List\try_nth;
use function List\unique;

test('map', function () {
    expect([5, 4, 3, 2, 1] |> map(fn(int $val, int $index) => $val + $index))->toBe([5, 5, 5, 5, 5]);
});

test('filter', function () {
    expect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10] |> filter(fn(int $val) => $val % 2 === 0))->toBe([2, 4, 6, 8, 10]);
});

test('partition', function () {
    list($even, $odd) = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] |> partition(fn(int $num) => $num % 2 === 0);
    expect($even)->toBe([2, 4, 6, 8, 10]);
    expect($odd)->toBe([1, 3, 5, 7, 9]);
});

test('find', function () {
    $res = [1, 3, 5, 6, 10] |> find(fn(int $num) => $num % 2 === 0);
    expect($res)->toBe(6);

    $res = [1, 3, 5, 7] |> find(fn(int $num) => ($num % 2) === 0);
    expect($res)->toBeNull();
});

test('find_index', function () {
    $res = [1, 3, 5, 6, 10] |> find_index(fn(int $num) => $num % 2 === 0);
    expect($res)->toBe(3);

    $res = [1, 3, 5, 7] |> find_index(fn(int $num) => $num % 2 === 0);
    expect($res)->toBeNull();
});

test('for_all', function () {
    $vars = [1, 2, 3, 4];
    [1, 2, 3, 4] |> for_all(function (int $num, int $index) use ($vars) {
        expect($num)->toBe($vars[$index]);
    });
});

test('some', function () {
    expect([1, 3, 5, 7, 10] |> some(fn(int $num) => $num % 2 === 0))->toBeTrue();
});

test('every', function () {
    expect([2, 4, 6, 8, 10] |> every(fn(int $num) => $num % 2 === 0))->toBeTrue();
});

test('head', function () {
    expect([1, 2] |> head())->toBe(1);
});

test('tail', function () {
    expect([1, 2] |> tail())->toBe([2]);
    expect([1, 2, 3, 4] |> tail())->toBe([2, 3, 4]);
    expect([1] |> tail())->toBe([]);
    expect([] |> tail())->toBe([]);
});

test('first', function () {
    expect([1, 2, 3, 4] |> first())->toBe(1);
});

test('second', function () {
    expect([1, 2, 3, 4] |> second())->toBe(2);
});

test('third', function () {
    expect([1, 2, 3, 4] |> third())->toBe(3);
});

test('last', function () {
    expect([1, 2, 3, 4] |> last())->toBe(4);
});

test('init', function () {
    expect(init(fn(int $num) => $num + 1, 3))->toBe([1, 2, 3]);
});

test('append', function () {
    expect([1, 2, 3] |> append([4, 5, 6]))->toBe([1, 2, 3, 4, 5, 6]);
});

test('cons', function () {
    $arr = [1, 2, 3, 4];
    expect([1, 2, 3, 4] |> cons(5))->toBe([1, 2, 3, 4, 5]);
    expect($arr)->toBe([1, 2, 3, 4]);
});

test('flatten', function () {
    expect([[[1, 2], 3], [4, [5]], 6] |> flatten())->toBe([1, 2, 3, 4, 5, 6]);
});

test('flat_map', function () {
    expect(['hello world', 'this is a list with', 'strings'] |> flat_map(fn(string $s) => explode(' ', $s)))
        ->toBe(['hello', 'world', 'this', 'is', 'a', 'list', 'with', 'strings']);
});

test('take', function () {
    expect([1, 2, 3, 4, 5] |> take(2))->toBe([1, 2]);
    expect([1, 2, 3, 4, 5] |> take(10))->toBe([1, 2, 3, 4, 5]);
});

test('take_while', function () {
    expect([2, 4, 6, 7, 10] |> take_while(fn(int $num) => $num % 2 === 0))->toBe([2, 4, 6]);
});

test('drop', function () {
    expect([1, 2, 3, 4, 5] |> drop(2))->toBe([3, 4, 5]);
});

test('drop_while', function () {
    expect([2, 4, 6, 7, 10] |> drop_while(fn(int $num) => $num % 2 === 0))->toBe([7, 10]);
});

test('slice', function () {
    expect([1, 2, 3, 4, 5] |> slice(2, 1))->toBe([3]);
});

test('sort', function () {
    $lst = [5, 100, 4, 3, 2, 1];
    expect([5, 100, 4, 3, 2, 1] |> sort_list(fn(int $a, int $b) => $a <=> $b))->toBe([1, 2, 3, 4, 5, 100]);
    expect($lst)->toBe([5, 100, 4, 3, 2, 1]);
});

test('unique', function () {
    expect([1, 1, 2, 2, 3] |> unique())->toBe([1, 2, 3]);
});

test('sort_unique', function () {
    $lst = [100, 1, 1, 0];
    expect([100, 1, 1, 0] |> sort_unique(fn(int $a, int $b) => $a <=> $b))->toBe([0, 1, 100]);
});

test('group_by', function () {
    $result = [1, 2, 3, 4, 5] |> group_by(fn(int $num) => $num % 2 === 0 ? 'even' : 'odd');
    expect($result['even'])->toBe([2, 4]);
    expect($result['odd'])->toBe([1, 3, 5]);
});

test('try_nth', function () {
    expect([1, 2, 3] |> try_nth(1))->toBe(2);
    expect([1, 2, 3] |> try_nth(4))->toBeNull();
});

test('pipeline from readme', function () {
    $transactions = [
        ['id' => 1, 'user' => 'Alice', 'amount' => 600, 'cat' => 'Tech'],
        ['id' => 2, 'user' => 'Bob', 'amount' => 150, 'cat' => 'Tech'],
        ['id' => 3, 'user' => 'Alice', 'amount' => 700, 'cat' => 'Tech'],
        ['id' => 4, 'user' => 'Charlie', 'amount' => 800, 'cat' => 'Garden'],
        ['id' => 5, 'user' => 'Bob', 'amount' => 900, 'cat' => 'Tech'],
        ['id' => 6, 'user' => 'Alice', 'amount' => 50, 'cat' => 'Tech'],
    ];

    $uniqueUsers = $transactions
        |> filter(fn(array $t) => $t['cat'] === 'Tech')
        |> filter(fn(array $t) => $t['amount'] > 500)
        |> map(fn(array $t) => $t['user'])
        |> unique();

    /** @var list<string> $uniqueUsers */
    $topTechUsers = $uniqueUsers
        |> sort_list(fn(string $a, string $b) => strcmp($a, $b))
        |> take(2);

    expect($topTechUsers)->toBe(['Alice', 'Bob']);
});
