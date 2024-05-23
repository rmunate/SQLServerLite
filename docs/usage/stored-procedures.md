---
title: Stored Procedures
editLink: true
outline: deep
---

# Stored Procedures

Depending on the architecture of your solution, much of the workload may be handled by the database itself. Therefore, you can use Stored Procedures to encapsulate the complete logic to be executed. Let's see how you can call and execute them with this package, depending on whether they are transactional (i.e., they do not return values) or procedural with data return.

## Direct Execution

You can execute the query directly with a SQL Server-specific query string.

Without data return (`executeTransactionalProcedure`).

```php
$dni = 1234567890;
$country = 'Colombia';

SQLServer::connection('example')
         ->executeTransactionalProcedure("EXEC UpdateValues {$dni}, {$country}");
```

With data return (`executeProcedure`).

```php
$dni = 1234567890;
$country = 'Colombia';

$data = SQLServer::connection('example')
                 ->executeProcedure("EXEC GetValues {$dni}, {$country}")
                 ->get();
```

## Prepared Execution

It is always advisable to prepare the query.

Without data return (`executeTransactionalProcedure`).

```php
SQLServer::connection('example')
         ->executeTransactionalProcedure("EXEC UpdateValues :dni, :country")
         ->params([
             'dni' => 1234567890,
             'country' => 'Colombia',
         ]);
```

With data return (`executeProcedure`).

```php
$data = SQLServer::connection('example')
               ->executeProcedure("EXEC GetValues :dni, :country")
               ->params([
                   'dni' => 1234567890,
                   'country' => 'Colombia',
               ])
               ->get();
```

In the above scenario, we defined a parameter with the syntax `:params`, which is then set with a value using the `params()` method. This makes the code much more readable and the statements much safer, protecting against SQL injection.