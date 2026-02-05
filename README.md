# list

A minimal set of functions for transforming lists

```php
<?php

$transactions = [
    ['id' => 1, 'user' => 'Alice', 'amount' => 600, 'cat' => 'Tech'],
    ['id' => 2, 'user' => 'Bob', 'amount' => 150, 'cat' => 'Tech'],
    ['id' => 3, 'user' => 'Alice', 'amount' => 700, 'cat' => 'Tech'],
    ['id' => 4, 'user' => 'Charlie', 'amount' => 800, 'cat' => 'Garden'],
    ['id' => 5, 'user' => 'Bob', 'amount' => 900, 'cat' => 'Tech'],
    ['id' => 6, 'user' => 'Alice', 'amount' => 50, 'cat' => 'Tech'],
];

$topTechUsers = $transactions
    |> filter(fn($t) => $t['cat'] === 'Tech', ...)         // 1. Only Tech
    |> filter(fn($t) => $t['amount'] > 500, ...)           // 2. High value only
    |> map(fn($t) => $t['user'], ...)                      // 3. Just the names
    |> unique(...)                                         // 4. No duplicates
    |> sort_list(fn($a, $b) => strcmp($a, $b), ...)        // 5. Alphabetical
    |> take(..., 2);                                       // 6. Top 2

print_r($topTechUsers);

/* Output:
Array
(
    [0] => Alice
    [1] => Bob
)
*/

```

## License

MIT
