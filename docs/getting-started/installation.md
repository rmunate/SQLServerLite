---
title: Installation
editLink: true
outline: deep
---

# Installation

## Requirements

SQLServer Lite 3.0 requires PHP 7.4 or 8.x. If you use it in a Laravel application, Laravel also sets a minimum PHP version:

| Laravel version | Minimum PHP version with SQLServer Lite |
| --- | --- |
| 8.x | 7.4 |
| 9.x | 8.0.2 |
| 10.x | 8.1 |
| 11.x and 12.x | 8.2 |
| 13.x | 8.3 |

To connect to SQL Server, enable the `pdo_sqlsrv` PHP extension and install a compatible Microsoft ODBC driver. Choose a driver release that supports your PHP version and operating system; see the [Microsoft driver requirements](https://learn.microsoft.com/en-us/sql/connect/php/system-requirements-for-the-php-sql-driver). The package opens its own PDO connection and does not use Laravel's database manager.

## Composer

To install the dependency using Composer, run the following command:

```bash
composer require "rmunate/sql-server-lite:^3.0"
```