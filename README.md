# SQLServer (Libreria para conexión con bases de datos SQLServer desde Laravel o PHP Estructurado.)
Una alternativa de Conexión a SQL Server desde Laravel y una excelente forma para conectar desde PHP Estructurado.

[![N|Solid](https://i.ibb.co/ZLzQTpm/Firma-Git-Hub.png)](#)

Clase para conexión a servidores SQL Server, con uso de métodos claros y fáciles de conexión.
Esta clase es una alternativa a la conexión por medio de las clases predeterminadas de Laravel.
Puede Implementarse en PHP Estructurado, el metodo que no se podria usar seria `->collect()` el cual es exclusivo para Laravel.
Clase liviana y precisa para ejecutar cualquier tipo de sentencia en la base de datos.

## Instalación
# Instalación a través de Composer

```sh
composer require rmunate/sql-server-lite
```
Importante: El Driver ODBC >= 17 debe Estar Instalado en la Maquina.
https://learn.microsoft.com/es-es/sql/connect/odbc/download-odbc-driver-for-sql-server
De igual manera deben estar activas las extenciones en el PHP.ini

# Para llamado en los controladores, invoque el uso.

```sh
use Rmunate\SqlServerLite\SQLServer;
```

# METODOS DE CONEXIÓN
## METODO #1 (RECOMENDADO)
- CONEXIÓN A TRAVÉS DE HELPER QUE LEE LAS VARIABLES DE ENTORNO (LARAVEL), O QUE CONTENDRÁ LAS CREDENCIALES DE CONEXIÓN (PHP ESTRCUTURADO)
LARAVEL: Valide en la documentación de laravel como crear sus Helpers Personalizados, y garantice que dentro del Composer.json, se encuentre para iniciar el archivo con el Autoload. (A continuación un Ejemplo.)

```sh
#En este caso se usará un archivo llamado Helpers.php que estará en la ubicacion app/Http, y dentro del archivo composer.json se aplicará la siguiente configuración.
"autoload-dev": {
    ...,
    "files":["app/Http/Helpers.php"]
},
```

El valor "instance" solo debe ir en los casos donde la conexión requiere una instancia especifica, de lo contrario no incluirlo en el helper.

- FUNCIÓN EN LOS HELPERS
```sh
function CREDENCIALES(){
    $credenciales = array(
        'server'   => env('DB_SQLSVR_NAME'),
        'instance' => env('DB_SQLSVR_INSTANCE'), #Solo usarla en caso donde se debe conectar a una instancia especifica. Donde no se use, no crear la variable de entorno.
        'database' => env('DB_SQLSVR_DATABASE'),
        'user'     => env('DB_SQLSVR_USER'),
        'password' => env('DB_SQLSVR_PASS')
    );
    return $credenciales;
}
```

- VARIABLES EN EL ENV LARAVEL.
```sh
DB_SQLSVR_NAME="10.25.21.170" #Use siempre una IP evite usar nombres canonicos.
DB_SQLSVR_INSTANCE="PROD" #Solo usarla en caso donde se debe conectar a una instancia especifica. Donde no se use, no crear la variable de entorno.
DB_SQLSVR_DATABASE="basededatos"
DB_SQLSVR_USER="usuario"
DB_SQLSVR_PASS="contraseña"
```

- GENERE LA CONEXION A LA BASE DE DATOS DE LA SIGUIENTE FORMA.
```sh

#Instance la clase de la siguiente forma, donde se ingrese en el metodo `database` la funciñon creada en el Helper
#Esta funcion será la que leera las variables des el ENV, se aconseja de esta forma para evitar exponer las credenciales.
#Tambien puede ingresar directamente el arreglo en el metodo (No Aconsejable)

SQLServer::database(CREDENCIALES())->...

```

## METODO #2 (SOLO LARAVEL)

- VARIABLES EN EL ENV LARAVEL.
Cree en el .ENV de laravel las variables de conexion con un prefijo unico, conservando la sintaxis a continuación mostrada.

```sh
#Nuestro prefijo unico será `MYDATA`
MYDATA_SQLSVR_NAME="10.25.21.170" #Use siempre una IP evite usar nombres canonicos.
MYDATA_SQLSVR_INSTANCE="PROD" #Solo usarla en caso donde se debe conectar a una instancia especifica. Donde no se use, no crear la variable de entorno.
MYDATA_SQLSVR_DATABASE="basededatos"
MYDATA_SQLSVR_USER="usuario"
MYDATA_SQLSVR_PASS="contraseña"
```

- GENERE LA CONEXION A LA BASE DE DATOS DE LA SIGUIENTE FORMA.
```sh

#Instance la clase de la siguiente forma, donde se ingrese en el metodo `env` el prefijo usado para las variables de entorno en el ENV

SQLServer::env('MYDATA')->...

```

# Comandos con retorno de Datos #

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->select("SELECT * FROM 'usuarios'")->get()` | Ejecute las consultas que requiera a la base de datos y genere la respuesta con el metodo `->get()`, usando sentencias propias de SQL, Se retornará un arreglo. |
| `SQLServer::database(CREDENCIALES())->setTimeOut(10)->select("SELECT * FROM 'usuarios'")->get()` | El metodo `->setTimeOut(10)` recibe un número entero no negativo que representa el período de tiempo de espera, en segundos. Cero (0) es el valor predeterminado y significa que no hay tiempo de espera. |
| `SQLServer::database(CREDENCIALES())->select("SELECT * FROM 'usuarios'")->getObjects()` | Ejecute las consultas que requiera a la base de datos y genere la respuesta con el metodo `->getObjects()` para retornar un arreglo de Objetos. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->get()` | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta con el metodo `->get()`, usando sentencias propias de SQL, Se retornarà un arreglo. |
| `SQLServer::database(CREDENCIALES())->noCount()->procedure("EXEC procedure")->get()` | El metodo `->noCount` puede ser necesario en consultas a procedimientos que contangan OpenQuerys o SubQuerys |


# Comandos Adicionales con retorno de Datos #

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->first()` | Trae el primer valor del arreglo retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->get()` | Trae todos los valores como arreglo retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getObjects()` | Trae todos los valores como objetos dentro de un arreglo. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->firstObject()` | Trae el primer valor como Objeto retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->last()` | Trae el ultimo valor del arreglo retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->lastObject()` | Trae el ultimo valor como Objeto retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->count()` | Cuenta el total de los registros de la Respuesta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->reverse()` | Retorna los valores de respuesta como arreglo en Reversa. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->reverseObjects()` | Retorna los valores de respuesta como objetos en Reversa. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->collect()` | Retorna la respuesta como colección de Laravel. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getFlip()` | Retorna la respuesta invirtiendo la columna con el valor obtenido. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getNamesColumns()` | Retorna el nombre de las columnas de la consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getRand(2)` | Retorna de forma aleatora la cantidad de registros solicitados en el metodo. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getSlice(2, 1, true)` | Alias del metodo array_slice de PHP. <https://www.php.net/manual/en/function.array-slice.php> |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->getColumn('name')` | Retorna la respuesta de una sola columna de la respuesta de la base de datos. |

# Comandos con retorno Booleano true o false
## Rollback y Commit Implicito.

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->update("UPDATE 'usuarios' SET 'nombre' = 'Raul' WHERE 'id' = 1")` | Actualice los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->insert("INSERT INTO 'users' ('id', 'name') VALUES (1, 'Administrador')")` | Inserte los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->delete("DELETE FROM 'users' WHERE 'id'=1")` | Elimine los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure", false)` | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta True o False (Procedimientos Sin Retorno De Datos.) |
| `SQLServer::database(CREDENCIALES())->check(["tabla1","tabla2",...])->delete("DELETE FROM 'users' WHERE 'id'=1")` | El metodo `->check()` valida y ejecuta todas las restricciones de llaves foraneas de la tabla o de las tablas ingresadas en el metodo, estas deben ir siempre dentro de un arreglo como se muestra en el ejemplo. |
| `SQLServer::database(CREDENCIALES())->noCheck(["tabla1","tabla2"])->delete("DELETE FROM 'users' WHERE 'id'=1")` | El metodo `->noCheck()` deshabilita las restricciones de llaves foraneas de la tabla o de las tablas ingresadas en el metodo, estas deben ir siempre dentro de un arreglo como se muestra en el ejemplo. |

## Desarrollador
- Ingeniero, Raúl Mauricio Uñate Castro
- raulmauriciounate@gmail.com
