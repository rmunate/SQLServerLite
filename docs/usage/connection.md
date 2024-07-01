---
title: Connection
editLink: true
outline: deep
---

# Connecting to SQL Server from Laravel

To connect to SQL Server from Laravel, follow these steps:

## Environment Variables

Let's define the environment variables within the `.env` file so that we can later call them from the configuration.

```
DB_SQLSVR_NAME="10.110.XX.XX"
DB_SQLSVR_PORT="1433"
DB_SQLSVR_INSTANCE="SOUTH"
DB_SQLSVR_DATABASE="ERP"
DB_SQLSVR_USER="USER"
DB_SQLSVR_PASS="PASS"
```

## Configuration

Now, create the connection within the `config/database.php` file as follows:

```php
// ...
return [

  'connections' => [

      //...

      'example' => [
          'host'     => env('DB_SQLSVR_NAME'),
          'instance' => env('DB_SQLSVR_INSTANCE'),
          'port'     => env('DB_SQLSVR_PORT'),
          'database' => env('DB_SQLSVR_DATABASE'),
          'username' => env('DB_SQLSVR_USER'),
          'password' => env('DB_SQLSVR_PASS'),
          'charset'  => 'utf8',
      ],

  ],
];

```

This way, your project will be ready to establish the connection.

## Validate Connection

This package provides a workaround for cases where the database connection cannot be established.

```php
// Import Package.
use Rmunate\SqlServerLite\SQLServer;

// Create Connection Instance.
$PDO = SQLServer::status("example");

// Validate if connected.
if($PDO->isConnected()){

  // Consume the database when the connection is guaranteed.
  $PDO->query()->select("SELECT field FROM table")->get();

} else {

  // Throw an exception for the error or define the process to execute.
  throw new Exception($PDO->getMessage());

}
```

The `status` method allows you to consume the database, validating a successful connection. If you wish, you can pass the maximum number of seconds you are willing to wait for the connection to be established as the second parameter.

```php
// Wait for 5 seconds.
$PDO = SQLServer::status("example", 5);
```

## Execute direct connection.

If you prefer a direct method of connection and consumption, you can use the following method:

```php
$PDO = SQLServer::connection('example');
```

Note that `example` refers to the name you have given in the `config/database.php` file.