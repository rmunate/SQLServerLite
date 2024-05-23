---
title: Delete
editLink: true
outline: deep
---

# Delete

The process of deletion in a database always requires extreme care, and while our package makes it easy for you, it is essential to be cautious and have the correct conditions to avoid data loss.

## Direct Execution

Deletion of records without preparing the statement. (Direct Way)

```php
SQLServer::connection('example')
          ->delete("DELETE FROM products WHERE id = 1");
```

## Prepared Execution

The safest and correct way is to prepare the execution and use parameters.

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::connection('example')
          ->delete("DELETE FROM products WHERE id = :id")
          ->params([
             'id' => 1,
          ]);
```

In the above scenario, we defined a parameter with the syntax `:params`, which is then set with a value using the `params()` method. This makes the code much more readable and the statements much safer, protecting against SQL injection.