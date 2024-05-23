---
title: Update
editLink: true
outline: deep
---

# Update

You can easily execute the update of any value in the database with this package.

## Direct Execution

Update with direct query string, without using parameters.

Update a single record.

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::connection('example')
          ->update("UPDATE products SET product = 'Laptop' WHERE id = 1");
```

Update multiple records.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = [
    ['product' => 'Laptop', 'id' => 1],
    ['product' => 'Smartphone', 'id' => 2],
    ['product' => 'Tablet', 'id' => 3],
];

foreach ($data as $key => $value) {
    SQLServer::connection('example')
             ->update("UPDATE products SET product = '" . $value['product'] . "' WHERE id = " . $value['id']);
}

```

## Prepared Execution

Prepared update.

Update a single record.

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::connection('example')
          ->update("UPDATE products SET product = :product WHERE id = :id");
          ->params([
             'product' => 'Laptop',
             'id' => 1,
          ]);
```

Update multiple records.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = [
    ['product' => 'Laptop', 'id' => 1],
    ['product' => 'Smartphone', 'id' => 2],
    ['product' => 'Tablet', 'id' => 3],
];

foreach ($data as $key => $value) {
    SQLServer::connection('example')
              ->update("UPDATE products SET product = :product WHERE id = :id");
              ->params([
                  'product' => $value['product'],
                  'id' => $value['id'],
              ]);
}

```

In both of the above scenarios, we defined a parameter with the syntax `:params`, which is then set with a value using the `params()` method. This makes the code much more readable and the statements much safer, protecting against SQL injection.