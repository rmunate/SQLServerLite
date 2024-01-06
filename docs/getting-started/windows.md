---
title: Windows Drivers
editLink: true
outline: deep
---

# SQL Server Drivers on Windows

## Installation

1. Install the ODBC Drivers, preferably version 17. For recent SQL Server versions, you can use higher versions. To download the executable, visit the following link:

> [Download ODBC Driver for SQL Server](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server?view=sql-server-ver16#version-17)

2. Download the PHP drivers and extract them into the `ext` folder of the directory where you have the active PHP version. Use the following link:

> [Download PHP Drivers for SQL Server](https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server)

3. Uncomment or add the following extensions in the `php.ini` file in use.

```ini
extension=php_pdo_sqlsrv_82_ts_x64.dll
extension=php_sqlsrv_82_ts_x64.dll
```

**Restart your machine, and you're all set!**