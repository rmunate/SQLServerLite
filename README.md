# SQLServerLite (PHP | LARAVEL) 
> [![Raul Mauricio Uñate Castro](https://storage.googleapis.com/lola-web/storage_apls/RecursosCompartidos/LogoGithubLibrerias.png)](#)
## _Libreria para conexión con bases de datos SQLServer desde Laravel o PHP Estructurado._
Una alternativa de Conexión a SQL Server desde Laravel y una excelente forma para conectar desde PHP Estructurado.


- Clase para conexión a Bases De Datos SQL Server, con uso de métodos claros y fáciles de conexión.
- Esta clase es una alternativa a la conexión por medio de las clases predeterminadas de Laravel y una excelente opción para PHP sin Framework.
- Puede Implementarse en PHP Estructurado, el metodo que no se podria usar seria `->collect()` el cual es exclusivo para Laravel.
- Clase liviana y precisa para ejecutar cualquier tipo de sentencia en la base de datos.

# Instalación
## _Instalación a través de Composer_

```console
composer require rmunate/sql-server-lite v2.0.x-dev
```
> Importante: El Driver ODBC >= 17 y SQLServer deben estar instalados.
> Manual de Instalación en Linux Ubuntu:
> https://github.com/rmunate/SQLServerLite/blob/main/InstalacionSQLServer.md

## Para llamado en los controladores, invoque el uso.

```php
use Rmunate\SqlServerLite\SQLServer;
```

# METODOS DE CONEXIÓN
## METODO #1 (RECOMENDADO)
> Conexión a través de Helper que contenga las variables de entorno, en el caso de Laravel, deberá leerlas den .ENV y en el caso de PHP sin Framework, se podrá ingresar las credenciales dentro del Helper.


Cree sus Helpers Personalizados, y garantice que dentro del `composer.json`, se encuentre para iniciar el archivo con el Autoload. (A continuación un Ejemplo.)

```javascript
// En este caso se usará un archivo llamado Helpers.php que estará en la ubicacion app/Global/Helpers.php, para que se cargue al iniciar la plataforma, dentro del archivo composer.json se aplicará la siguiente configuración.
"autoload-dev": {
    ...,
    "files":["app/Global/Helpers.php"]
},
```

Crear funcion Helper con retorno de un arreglo con las credenciales de conexión.

FUNCIÓN EN LOS HELPERS

```php
//Para que sea mas clara la conexión en caso de que use varias, ponga de nombre de función el nombre de la base de datos.
function CREDENCIALES(){
    $credenciales = array(
        'server'   => env('DB_SQLSRV_NAME'),
        'instance' => env('DB_SQLSRV_INSTANCE'), #Solo usarla en caso donde se debe conectar a una instancia especifica. Donde no se use, no crear la variable de entorno.
        'database' => env('DB_SQLSRV_DATABASE'),
        'user'     => env('DB_SQLSRV_USER'),
        'password' => env('DB_SQLSRV_PASS')
    );
    return $credenciales;
}
```

El valor "instance" dentro del array, solo debe ir en los casos donde la conexión requiere una instancia especifica, de lo contrario NO INCLUIRLO en el helper.

VARIABLES EN EL ENV LARAVEL.

Se debe garantizar que en el ENV de Laravel esten las variables que se estan llamando con la funcion env(...)

```php
DB_SQLSRV_NAME="10.25.21.170" # Use siempre la IP evite usar nombres.
DB_SQLSRV_INSTANCE="PROD" # Solo usarla en caso donde se debe conectar a una instancia especifica. Donde no se use, no crear la variable de entorno.
DB_SQLSRV_DATABASE="basededatos"
DB_SQLSRV_USER="usuario"
DB_SQLSRV_PASS="contraseña"
```

GENERE LA CONEXION A LA BASE DE DATOS DE LA SIGUIENTE FORMA.
Instance la clase de la siguiente forma, donde se ingrese en el metodo `database` la funciñon creada en el Helper.
Esta funcion será la que leera las variables des el ENV, se aconseja de esta forma para evitar exponer las credenciales.
Tambien puede ingresar directamente el arreglo en el metodo (No se recomienda, por CleanCode, se recomienda que las credenciales de Conexión esten en un solo lugar).

```php
SQLServer::database(CREDENCIALES())->...
```

## METODO #2 (SOLO LARAVEL)

VARIABLES EN EL ENV LARAVEL.
Cree en el .ENV de Laravel las variables de conexión con un prefijo único, conservando la sintaxis a continuación mostrada.
Nuestro prefijo unico será `MYDATA`

```php
MYDATA_SQLSRV_NAME="10.25.21.170" # Use siempre la IP evite usar nombres.
MYDATA_SQLSRV_INSTANCE="PROD" #S olo usarla en caso donde se debe conectar a una instancia especifica. Donde no se use, no crear la variable de entorno.
MYDATA_SQLSRV_DATABASE="basededatos"
MYDATA_SQLSRV_USER="usuario"
MYDATA_SQLSRV_PASS="contraseña"
```

GENERE LA CONEXION A LA BASE DE DATOS DE LA SIGUIENTE FORMA.
Instance la clase de la siguiente forma, donde se ingrese en el metodo `env` el prefijo usado para las variables de entorno en el ENV
```php
SQLServer::env('MYDATA')->...
```

# _Metodo Para Confirmacion de Conexión_
Se puede usar con los dos metodos de conexión.

```php
$connection = SQLServer::database(CREDENCIALES())->status(); 
//Retornar un objeto con el detalle de la conexión.

if($connection->status){
    //Hacer consulta ya que existe conexión con la base de datos
    $data = $connection->query->select("...............")->get();
} else {
    //No hubo conexion a la base de datos...
    //Retornar Error 500 o ejecutar un proceso específico.
}
```


# Metodos con retorno de Datos

| SINTAXIS | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->select("SELECT * FROM 'usuarios'")->get()` | Ejecute las consultas que requiera a la base de datos y genere la respuesta con el metodo `->get()`, usando sentencias propias de SQL. **Los datos retornados serán un arreglo.** |
| `SQLServer::database(CREDENCIALES())->setTimeOut(10)->select("SELECT * FROM 'usuarios'")->get()` | El metodo `->setTimeOut(10)` recibe un número entero no negativo que representa el período de tiempo de espera, en segundos. El valor **predeterminado es 0** y significa que no hay tiempo de espera. |
| `SQLServer::database(CREDENCIALES())->select("SELECT * FROM 'usuarios'")->getObjects()` | Ejecute las consultas que requiera a la base de datos y genere la respuesta con el metodo `->getObjects()` para retornar un **arreglo de objetos**, a los cuales podrá ingresar a traves de **->**. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->get()` | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta con el metodo `->get()`, usando sentencias propias de SQL, **Se retornará un arreglo**. |
| `SQLServer::database(CREDENCIALES())->noCount()->procedure("EXEC procedure")->get()` | El metodo `->noCount` puede ser necesario en consultas a procedimientos que contangan OpenQuerys o SubQuerys. |


# Metodos Adicionales Con Retorno De Datos

| SINTAXIS | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->first()` | Trae el primer valor del arreglo retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->get()` | Trae todos los valores retornados en la Consulta como arreglo. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getObjects()` | Trae todos los valores como objetos dentro de un arreglo. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->firstObject()` | Trae el primer valor retornado en la Consulta como un objeto. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->last()` | Trae el ultimo valor retornado en la Consulta como un arreglo . |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->lastObject()` | Trae el ultimo valor retornado en la Consulta como Objeto. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->count()` | Cuenta el total de los registros retornados de la Respuesta, no traé data adicional. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->reverse()` | Retorna los valores de la respuesta en un arreglo de forma inversa. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->reverseObjects()` | Retorna los valores de la respuesta en un arreglo de objetos de forma inversa. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->collect()` | Retorna la respuesta como colección de Laravel. **Solo Funciona En Laravel ^5.5.x** |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getFlip()` | Retorna la respuesta invirtiendo la columna con el valor obtenido. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getNamesColumns()` | Retorna el nombre de las columnas de la respuesta de la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getRand(2)` | Retorna de forma aleatora la cantidad de registros solicitados en el metodo. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getSlice(2, 1, true)` | Alias del metodo array_slice de PHP. <https://www.php.net/manual/en/function.array-slice.php> |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getColumn('name')` | Retorna la respuesta de una sola columna. |

# Metodos Con Retornos Booleanos (true | false)
## _Rollback y Commit Implicito._

Todos los metodos que no sean de consulta, cuantan con el Rollback y el Commit Implicitó en la clase, para nó crear registros basura en la base de datos, en los casos donde hayan errores.

| SINTAXIS | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->update("UPDATE 'usuarios' SET 'nombre' = 'Raul' WHERE 'id' = 1")` | Actualice los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->insert("INSERT INTO 'users' ('id', 'name') VALUES (1, 'Administrador')")` | Inserte los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->delete("DELETE FROM 'users' WHERE 'id'=1")` | Elimine los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure", false)` | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta True o False **(Procedimientos Sin Retorno De Datos.)** |
| `SQLServer::database(CREDENCIALES())->check(["tabla1","tabla2",...])->delete("DELETE FROM 'users' WHERE 'id'=1")` | El metodo `->check()` valida y ejecuta todas las restricciones de llaves foraneas de la tabla o de las tablas ingresadas en el metodo, estas deben ir siempre dentro de un arreglo como se muestra en el ejemplo aunque sea solo una. |
| `SQLServer::database(CREDENCIALES())->noCheck(["tabla1","tabla2"])->delete("DELETE FROM 'users' WHERE 'id'=1")` | El metodo `->noCheck()` deshabilita las restricciones de llaves foraneas de la tabla o de las tablas ingresadas en el metodo, estas deben ir siempre dentro de un arreglo como se muestra en el ejemplo, aunque sea solo una. |

## Mantenedores
- Ingeniero, Raúl Mauricio Uñate Castro (raulmauriciounate@gmail.com)
- Ingeniero, Jorge Hernan Castañeda (ds.jorgecastaneda@gmail.com)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)