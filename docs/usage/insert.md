---
title: Insert
editLink: true
outline: deep
---

# Insert

To perform insertions in SQL Server, you have various options depending on your system requirements.

## Direct Execution

If you prefer to first test statements at the database level before using them in your software, you can execute statements as follows:

Insert a single record.

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::connection('example')
          ->insert("INSERT INTO products (product, price) VALUES ('Laptop', 1200)");
```

Insert multiple records.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = [
    ['product' => 'Laptop', 'price' => '1200'],
    ['product' => 'Smartphone', 'price' => '800'],
    ['product' => 'Tablet', 'price' => '500'],
];

foreach ($data as $key => $value) {
    SQLServer::connection('example')
             ->insert("INSERT INTO products (product, price) VALUES ('" . $value['product'] . "', ". $value['price'] . ")");
}

```

## Prepared Execution

The recommended way to execute an SQL statement is by preparing it (a prepared SQL query is a technique used to execute SQL queries efficiently and securely in a database management system (DBMS)).

Let's see how this can be done:

Insert a single record.

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::connection('example')
          ->insert("INSERT INTO products (product, price) VALUES (:product, :price)")
          ->params([
             'product' => 'Laptop',
             'price' => 1200,
          ]);
```

Insert multiple records.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = [
    ['product' => 'Laptop', 'price' => '1200'],
    ['product' => 'Smartphone', 'price' => '800'],
    ['product' => 'Tablet', 'price' => '500'],
];

foreach ($data as $key => $value) {
    SQLServer::connection('example')
              ->insert("INSERT INTO products (product, price) VALUES (:product, :price)")
              ->params([
                  'product' => $value['product'],
                  'price' => $value['price'],
              ]);
}

```

In both of the above scenarios, we defined a parameter with the syntax `:params`, which is then set with a value using the `params()` method. This makes the code much more readable and the statements much safer, protecting against SQL injection.

## Mass Insertion

If you don't want to run a foreach loop for each iteration, you can pass an array of arrays as a parameter. However, *it is crucial that the keys of the arrays match the parameters specified in the query string*.

```php
use Rmunate\SqlServerLite\SQLServer;

SQLServer::connection('example')
            ->insert("INSERT INTO products (product, price) VALUES (:product, :price)")
            ->params([
                ['product' => 'Laptop', 'price' => '1200'],
                ['product' => 'Smartphone', 'price' => '800'],
                ['product' => 'Tablet', 'price' => '500'],
            ]);

```

## Retrieve ID

In many cases, you may need to retrieve the ID of the record or records added to the database. For this, you can use the `insertGetId()` method, which can be used for both individual and mass insertion, retrieving an array of added IDs.

**The table must have a column (id) that is the primary key and its value is auto-incremental**

Individual Insertion

```php
use Rmunate\SqlServerLite\SQLServer;

$id = SQLServer::connection('example')
            ->insertGetId("INSERT INTO products (product, price) VALUES (:product, :price)")
            ->params([
               'product' => 'Laptop',
               'price' => 1200,
            ]);

dd($id); // (int) 1 
```

Insert multiple records.

```php
use Rmunate\SqlServerLite\SQLServer;

$ids = SQLServer::connection('example')
            ->insertGetId("INSERT INTO products (product, price) VALUES (:product, :price)")
            ->params([
                ['product' => 'Laptop', 'price' => '1200'],
                ['product' => 'Smartphone', 'price' => '800'],
                ['product' => 'Tablet', 'price' => '500'],
            ]);

dd($ids); // (array) [1, 2, 3]
```