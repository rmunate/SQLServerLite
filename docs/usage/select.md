---
title: Select
editLink: true
outline: deep
---

# Select

The most common process in any software is constantly querying information from the database. Our package simplifies this process, ensuring the use of a single persistent database connection instance throughout the lifecycle of the HTTP request or Console application, in the case of a CLI application.

## Direct Execution

You can execute the query directly with a SQL Server-specific query string.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->get();
```

Queries that return more than one record will yield an instance of `\Rmunate\SqlServerLite\Response\SQLServerResponse` object, to which any method available in Laravel collections (https://laravel.com/docs/collections) can be applied.

## Prepared Execution

You can also prepare the query to determine which filters to apply.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products WHERE category = :category")
               ->params([
                  'category' => 'tech',
               ])
               ->get();
```

In the above scenario, we defined a parameter with the syntax `:params`, which is then set with a value using the `params()` method. This makes the code much more readable and the statements much safer, protecting against SQL injection.