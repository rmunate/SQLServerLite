---
title: Final Methods
editLink: true
outline: deep
---

# Final Methods

Tendras a tu disposicion una seria de metodos finales orientados a ser iguales a los de Query Builder de Laravel para que se pueda aplicar la expericia adquirida previamente con este framework

## Get

El metodo `get` se debe emplear cuando la consulta retorno mas de un registro. Esto retorna una instancia del objeto `\Rmunate\SqlServerLite\Response\SQLServerResponse` que permitirá aplicarle cualquiera de los metodos disponibles en las colecciones de Laravel (https://laravel.com/docs/collections). 

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->get();

```

## OrderBy

El metodo `orderBy` sirve para ordenar los datos de la consulta ejecutada previo a ejecutar el metodo final `get`.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->orderBy("campo", "DESC") // (default) "ASC"
               ->get();
```

## First

El metodo `first` retorna el valor del primer registro, permitiendo acceder a sus valores a traves de `->` como propiedades de objeto, gracias a que este metodo retorna una instancia del objeto `\Rmunate\SqlServerLite\Response\SQLServerRow`.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->first();
```

## Last

El metodo `last` retorna el valor del ultimo registro, permitiendo acceder a sus valores a traves de `->` como propiedades de objeto, gracias a que este metodo retorna una instancia del objeto `\Rmunate\SqlServerLite\Response\SQLServerRow`.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->last();
```

## Count

El metodo `count` retorna la cuenta del total de registros encontrados. En estos casos evita usar el (*) ya que consultar toda la informacion para una cuenta es ineficiente.

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT id FROM products")
               ->count();
```

## All

El metodo `all` se debe emplear cuando la consulta retorno mas de un registro (Es un alias de GET). Esto retorna una instancia del objeto `\Rmunate\SqlServerLite\Response\SQLServerResponse` que permitirá aplicarle cualquiera de los metodos disponibles en las colecciones de Laravel (https://laravel.com/docs/collections). 

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->all();

```

## Pluck

The `pluck` method retrieves all of the values for a given key:

```php
use Rmunate\SqlServerLite\SQLServer;

$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->pluck('name');
 
// ['Desk', 'Chair']

```

You may also specify how you wish the resulting collection to be keyed:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->pluck('name', 'product_id');
  
// ['prod-100' => 'Desk', 'prod-200' => 'Chair']
```

## Value

The `value` method retrieves a given value from the first element of the collection:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->value('price');
  
// 1800
```

## Chunk

The `chunk` method breaks the collection into multiple, smaller collections of a given size:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->chunk(10);

```

Lo anterior parte la consulta en subarrays de a 10 registros.

## Lazy

The `lazy` method returns a new `LazyCollection` instance from the underlying array of items:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->lazy();

// Illuminate\Support\LazyCollection
```

## Max

The `max` method returns the maximum value of a given key:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->max("price");

// 23000
```

## Min

The `min` method returns the minimum value of a given key:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->min("price");

// 150
```

## Sum

The `sum` method returns the sum of all items in the collection:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products WHERE category = :category")
               ->params([
                  'category' => 'tech'
               ])
               ->sum("price");

// 1520000000
```

## Avg

The `avg` method returns the average value of a given key:

```php
$data = SQLServer::connection('example')
               ->select("SELECT * FROM products")
               ->avg("price");

// 2780
```