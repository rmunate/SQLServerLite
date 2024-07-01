---
title: Attributes
editLink: true
outline: deep
---

# Connection Attributes

This package provides the convenience of easily adjusting connection attributes as needed:

## SetTimeOut

Sets a specific configuration attribute for the SQL Server connection using PDO (PHP Data Objects).

In particular, the method is used to define the maximum time, in seconds, a query can take before being interrupted. This attribute controls the timeout for executing an SQL query.

```php
$PDO = SQLServer::connection('example')->setTimeOut(5);
```

## SetErrorMode

Sets the error handling method based on the provided value.

In 'SILENT' mode, PDO attempts the operation without issuing any warning or error. If an error occurs, PDO simply ignores it and continues program execution. This mode is useful if you prefer to handle errors manually and do not want PDO to automatically emit messages.

In 'WARNING' mode, PDO performs the operation and issues a warning if an error occurs. However, program execution continues. You can capture these warnings in your code and handle them as needed. This mode is useful if you want to be aware of errors but still allow the program to continue running.

In 'EXCEPTION' mode, PDO performs the operation and throws a PDOException if an error occurs. This will halt normal program execution and allow you to capture and handle the exception according to your needs. This mode is useful for a more structured error handling through exceptions.

```php
$PDO = SQLServer::connection('example')->setErrorMode('EXCEPTION');
```

## SetEncoding

The `setEncoding` method allows configuring the character set for the SQL Server connection. This character set influences how data is handled and stored in the database. The default is UTF-8. Possible values are: 'BINARY', 'UTF8', 'SYSTEM', 'DEFAULT'

```php
$PDO = SQLServer::connection('example')->setEncoding('UTF8');
```

## SetDirectQuery

The `setDirectQuery` method provides a simple way to activate direct query mode for the SQL Server connection according to your specific needs. However, be aware of the implications and considerations associated with this mode before using it in your application.

```php
$PDO = SQLServer::connection('example')->setDirectQuery();
```

## SetAnsiNulls

The `setAnsiNulls` method allows setting the state of the `ANSI_NULLS` option for the database session. This option controls how NULL values are handled in comparisons and operations within SQL queries.

The `ANSI_NULLS` option in Microsoft SQL Server controls the behavior of comparisons and operations involving `NULL` values in SQL queries, following or not following ANSI SQL standards.

When `ANSI_NULLS` is set to 'ON' (the default setting), handling of `NULL` follows the ANSI SQL standard specification. In this mode:

- Comparing a value with `NULL` using the equality operator (`=`) does not return `true` or `false`. Instead, the result is unknown (unknown, unknown) based on the three-state logic (true, false, unknown) used to handle `NULL` in SQL. This means that the result of the comparison with `NULL` is neither true nor false but unknown.

When `ANSI_NULLS` is set to 'OFF', comparisons with `NULL` follow different rules. In this mode:

- Comparing a value with `NULL` using the equality operator (`=`) returns `true` if the value is `NULL` and `false` if it is not.

So, changing the `ANSI_NULLS` setting affects how comparisons with `NULL` values are evaluated in your SQL queries. The way this option is configured can have a significant impact on the logic of your queries, especially if you have written queries that depend on the specific behavior of the `ANSI_NULLS` setting.

```php
$PDO = SQLServer::connection('example')->setAnsiNulls("OFF") // ON - OFF;
```

## SetAnsiPadding

The `setAnsiPadding` method allows setting the state of the ANSI_PADDING option for the database session. This option controls how string values should be treated in comparisons and concatenation operations.

The ANSI_PADDING option affects how string values are treated in comparisons and concatenation operations. Before changing this setting, make sure you understand how it may affect the logic of your queries.

```php
$PDO = SQLServer::connection('example')->setAnsiPadding("OFF") // ON - OFF;
```

## SetAnsiWarnings

The `setAnsiWarnings` method allows setting the state of the ANSI_WARNINGS option for the database session. This option controls whether warnings should be generated when executing queries that produce non-deterministic or unpredictable results according to the ANSI SQL specification.

```php
$PDO = SQLServer::connection('example')->setAnsiWarnings("OFF") // ON - OFF;
```

## SetArithAbort

The `setArithAbort` method allows setting the state of the ARITHABORT option for the database session. This option controls whether overflow errors and division by zero errors generate warnings and terminate query execution.

```php
$PDO = SQLServer::connection('example')->setArithAbort("OFF") // ON - OFF;
```

## SetNoCount

The `noCount` method allows setting the state of the NOCOUNT option for the database session. The NOCOUNT option controls whether the count of rows affected by a Transact-SQL statement should be returned as part of the result set.

If you are executing a Stored Procedure that contains `PRINT`, it is convenient to use this method to omit them and ensure the proper execution of the process without generating exceptions in PHP.

```php
$PDO = SQLServer::connection('example')->setNoCount("OFF") // ON - OFF;
```