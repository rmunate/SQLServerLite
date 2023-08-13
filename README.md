# SQLServelLite (PHP & Laravel Framework) v1.x

![Logo](https://github.com/rmunate/SQLServerLite/assets/91748598/357847a5-917a-47cb-a554-bb6a99e8422d)

*Una forma, fácil, rápida y eficiente de usar bases de datos SQL Server en tus desarrollos con PHP Puro y/o con Laravel.*

------------

## Caracteristicas De La Libreria
1. [Instalación](#pendiente)
2. [Crear Conexiones](#pendiente)
	- [Desde Arreglo](#pendiente)
	- [Desde .ENV](#pendiente)
	- [Desde Conexiones Laravel](#pendiente)
3. [Testeo Conección](#pendiente)
4. [Sentencias](#pendiente)
	- [SELECT (Seleccionar)](#pendiente)
	- [UPDATE (Actualizar)](#pendiente)
	- [INSERT (Insertar)](#pendiente)
	- [DELETE (Eliminar)](#pendiente)
	- [STORE PROCEDURE (Procedimientos Almacenados)](#pendiente)
5. [Metodos Tratamiento De Datos](#pendiente)
	- [Reverse (Orden Inverso)](#pendiente)
	- [Unique (Elementos Unicos)](#pendiente)
	- [Sort (Ordenar)](#pendiente)
	- [Flip (Intercambiar Indices Y Valores)](#pendiente)
	- [Slice (Extraer Parte)](#pendiente)
	- [Column (Extraer Columna)](#pendiente)
	- [Merge (Mesclar)](#pendiente)
	- [RecursiveMerge (Mesclar Recursivamente)](#pendiente)
	- [Rand (Aleatorio)](#pendiente)
	- [KeyCase (Formato Indice)](#pendiente)
	- [Filter (Filtrat)](#pendiente)
	- [Map (Mapear)](#pendiente)
	- [Pad (Rellenar)](#pendiente)
	- [Pop (Quitar Ultimo)](#pendiente)
	- [Push (Agregar)](#pendiente)
	- [Shift (Quitar Primero)](#pendiente)
	- [UnShift (Añadir Al Inicio)](#pendiente)
	- [Values (Reindexar)](#pendiente)
6. [Metodos Finales](#pendiente)
	- [First (Primero)](#pendiente)
	- [Last (Ultimo)](#pendiente)
	- [Collect (Coleccion De Laravel)](#pendiente)
	- [Get (Obtener Todo)](#pendiente)
	- [Count (Cuenta)](#pendiente)
	- [SizeOf (Alias De Cuenta)](#pendiente)
	- [Chunk (Dividir En Fragmentos)](#pendiente)
	- [Reduce (Reducir Valor)](#pendiente)
7. [Control de Transacciones](#pendiente)
	- [BeginTransaction (Comenzar Transacción)](#pendiente)
	- [Commit (Guardar Cambios)](#pendiente)
	- [RollBack (Descartar Cambios)](#pendiente)
8. [Control de Llaves Foraneas](#pendiente)
	- [DisableForeignKeys (Inactivar Llaves Foraneas)](#pendiente)
	- [EnableForeignKeys (Activar Llaves Foraneas)](#pendiente)
9. [Validación De Drivers](#pendiente)
10. [Aplicación De Atributos](#pendiente)
	- [SetTimeOut (Tiempo De Espera)](#pendiente)
	- [SetErrorMode (Informe De Errores)](#pendiente)
	- [SetEncoding (Juego De Caracteres)](#pendiente)
	- [SetDirectQuery (Directo O Preparado)](#pendiente)
	- [SetAnsiNulls (Valores Columnas Calculadas)](#pendiente)
	- [SetAnsiPadding (Modo almacen Columna)](#pendiente)
	- [SetAnsiWarnings (Omitir Errores)](#pendiente)
	- [SetArithAbort (Abortar Por Errores)](#pendiente)
	-[SetNoCount (No Contar Filas)](#pendiente)


## Instalación
Para instalar la dependencia a través de Composer, debes ejecutar el siguiente comando:

```shell
composer require rmunate/sql-server-lite
```

## Crear Conexiones
La dependencia ofrece varias alternativas para establecer una conexión con una base de datos SQL. La elección de cuál utilizar estará a tu discreción. Para sistemas desarrollados en PHP estructurado, existen alternativas como (database, env). En el caso de Laravel, las tres siguientes opciones son válidas, aunque se recomienda el uso del archivo de configuración de conexiones que se encuentra en la carpeta `config` del framework.

### Conexión desde un Arreglo
Para crear una conexión a partir de un arreglo, podemos emplear la siguiente sintaxis. Puedes definir el arreglo en una ubicación específica de tu sistema y llamarlo dentro del método `database`. Esto sería considerado como una buena práctica, ya que evita la duplicación innecesaria del arreglo en múltiples lugares del sistema.

```php
use Rmunate\SqlServerLite\SQLServer;

$DB = SQLServer::database([
    'server'    => '10.110.220.20', 	// Obligatorio
    'instance'  => 'TEST',				// Opcional
    'port'      => '1433',				// Opcional
    'database'  => 'test',				// Obligatorio
    'user'      => 'username',			// Obligatorio
    'password'  => 'password',			// Obligatorio
    'charset'   => 'utf8',				// Opcional
]);
```

Definiendo los valores de conexion en un solo lugar del sistema.

```php
//Definir una constante
define('CREDENTIALS', [
    'server'    => '10.110.220.20',
    'instance'  => 'TEST',
    'port'      => '1433',
    'database'  => 'test',
    'user'      => 'username',
    'password'  => 'password',
    'charset'   => 'utf8',
]);

//Crear conexion con el llamado de la constante unica en el sistema
use Rmunate\SqlServerLite\SQLServer;

$DB = SQLServer::database(CREDENTIALS);
```

### Conexión utilizando valores del archivo .ENV

Si tu aplicación hace uso del archivo `.env` o si estás trabajando dentro del entorno de Laravel, puedes aprovechar el siguiente método para simplificar la construcción de conexiones a bases de datos. Al definir ciertos valores en el archivo `.env` según la estructura mostrada aquí, podrás agilizar la configuración de tus conexiones.

```php
TEST_SQLSRV_NAME=10.110.220.20   // Obligatorio
TEST_SQLSRV_INSTANCE=TEST        // Opcional
TEST_SQLSRV_PORT=1433            // Opcional
TEST_SQLSRV_DATABASE=test        // Obligatorio
TEST_SQLSRV_USER=username        // Obligatorio
TEST_SQLSRV_PASS=password        // Obligatorio
TEST_SQLSRV_CHARSET=utf8         // Opcional
```

Una característica clave es que cada valor de entorno comienza con "TEST", mientras que el resto del nombre se mantiene. Esto actuará como un identificador para inicializar la conexión.

```php
use Rmunate\SqlServerLite\SQLServer;

// Configura la conexión utilizando los valores del .env con el identificador "TEST"
$DB = SQLServer::env('TEST');

// A partir de aquí, estás listo para usar la conexión y ejecutar consultas en la base de datos
```

Esta técnica te permite centralizar y simplificar la configuración de las conexiones a la base de datos, facilitando el proceso y mejorando la mantenibilidad de tu código.

### Conexión específica para Laravel

Este método es diseñado específicamente para gestionar conexiones en el entorno del framework Laravel, siguiendo los estándares requeridos. Utiliza el archivo `database.php` ubicado en la carpeta `config`.

Dentro del array `connections` en dicho archivo, deberás crear una estructura similar a la siguiente. Aquí, utilizarás las variables de entorno personalizadas que hayas definido en tu archivo `.env`, las cuales contendrán los datos de conexión.

```php
'MyDatabase' => [
    'server'    => env('CUSTOM_SQL_SERVER'),         // Obligatorio
    'instance'  => env('CUSTOM_SQL_INSTANCE'),       // Opcional
    'port'      => env('CUSTOM_SQL_PORT'),           // Opcional
    'database'  => env('CUSTOM_SQL_DATABASE'),       // Obligatorio
    'user'      => env('CUSTOM_SQL_USER'),           // Obligatorio
    'password'  => env('CUSTOM_SQL_PASS'),           // Obligatorio
    'charset'   => env('CUSTOM_SQL_CHARSET', 'utf8') // Opcional
],
```

Ahora que hayas hecho esto, simplemente usaras la siguiente sintaxis para conectarte.

```php
use Rmunate\SqlServerLite\SQLServer;

$DB = SQLServer::connection('MyDatabase');
// A partir de aquí, estás listo para usar la conexión y ejecutar consultas en la base de datos
```

Este enfoque te permite configurar conexiones personalizadas en Laravel utilizando los valores de entorno definidos en tu archivo `.env`. Al proporcionar la información necesaria en las variables de entorno, podrás centralizar y simplificar la gestión de conexiones a bases de datos en tu aplicación Laravel, cumpliendo con las prácticas recomendadas.

## Testear Conexión

En diversos momentos, es crucial asegurarnos de que la conexión a la base de datos se haya establecido con éxito. En caso contrario, se debe informar al usuario de la aplicación. Esta medida suele ser necesaria en sistemas que trabajan con conexiones a través de VPN o que acceden a bases de datos que, debido a su tamaño, consumo o estructura, no están disponibles todo el tiempo. Además, en ocasiones, la incapacidad de conectarse a la base de datos puede depender de características específicas de la máquina que aloja el motor de base de datos, lo que puede dar lugar a diversos motivos y escenarios.

Para verificar si la conexión se ha establecido con éxito antes de interactuar con la base de datos, podemos hacer uso del método "status".

```php
$DB = SQLServer::connection('MyDatabase')->status();

if ($DB->status) {
    // Conexión exitosa
	// $DB->query->select(...)
} else {
    // Error en la conexión
}
```

Cada vez que se ejecute el método "status", obtendremos una respuesta con la siguiente estructura:

```javascript
{
  +"status": true,
  +"message": "Conexión exitosa",
  +"query": Rmunate\SqlServerLite\SQLServer {#302 ▶}
}
```

En esta respuesta, el valor de la propiedad "status" será `true` o `false`, según corresponda. La propiedad "message" proporcionará un mensaje que permitirá comprender el motivo de la falta de conexión en los casos en que no se haya establecido correctamente. Por último, la propiedad "query" contendrá una instancia de la clase SQLServer, que utilizaremos para ejecutar consultas sin necesidad de crear nuevas conexiones.

## Sentencias

Veamos cómo utilizar este paquete para ejecutar consultas en la base de datos. Si has utilizado el método `status` previamente, podrás acceder a través de `query`, tal como se explicó en el ejemplo anterior.

### SELECT

Puedes ejecutar consultas en la base de datos de manera sencilla y segura utilizando dos enfoques diferentes. La elección entre ellos dependerá de tus preferencias y de si deseas reutilizar la sentencia.

**Consulta Directa:** En este enfoque, se espera recibir la sentencia completa del `SELECT` que se va a realizar en la base de datos.

```php
$DB->select("SELECT * FROM prefix.table WHERE column = 'active'")->get();
```

**Consulta Preparada:** En esta opción, en lugar de enviar la consulta directa, puedes separar las condiciones de la consulta utilizando un arreglo asociativo. Cada clave en el arreglo representa un alias que se utilizará en la sentencia y se identifica mediante `:`

```php
$DB->select("SELECT * FROM prefix.table WHERE column = :search", [
    'search' => 'active'
])->get();
```

Estos métodos te permiten ejecutar consultas SELECT de manera eficiente y flexible, ofreciéndote opciones para adaptar tus consultas según tus necesidades específicas. 

### UPDATE

Para actualizar registros en la base de datos, también puedes emplear una sentencia directa o una preparada, según tu preferencia. En este caso, no se requieren métodos finales (como `get`, `first`, ...) ya que se trata de un proceso directo de actualización.
Este metodo retornará "true" o "false".

**Consulta Directa:** En este enfoque, puedes utilizar una sentencia UPDATE directa para modificar registros en la base de datos.

```php
$DB->update("UPDATE prefix.table SET value = 'XXXXX' WHERE column = '1003618689'");
```

**Consulta Preparada:** Si optas por una consulta preparada, puedes utilizar un arreglo asociativo para definir las condiciones de actualización.

```php
$DB->update("UPDATE prefix.table SET value = 'XXXXX' WHERE column = :search", [
	'search' => '1003618689'
]);
```

Estos métodos te permiten actualizar registros de manera eficiente y segura en la base de datos. Puedes elegir la opción que mejor se adapte a tus necesidades y preferencias. Ten en cuenta que, en ambos casos, no es necesario usar métodos finales, ya que la actualización se ejecuta directamente en la base de datos.

### INSERT
Aca te mostramos una sentencia simple de insercion, sin embargo puedes apoyarte de otras caracteristicas de la biblioeta para mejorar esta accesion como por ejemplo el control de rollback, o la obtencion del ID creado, esto siempre y cuando tu base de datos cuente con esta columna.





## Creator
- 🇨🇴 Raúl Mauricio Uñate Castro. (raulmauriciounate@gmail.com)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
