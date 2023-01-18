# SQLServer (Libreria para conexión con bases de datos SQLServer desde Laravel o PHP Estructurado.)
Alternativa de Conexión a SQL Server desde Laravel PHP

[![N|Solid](https://i.ibb.co/ZLzQTpm/Firma-Git-Hub.png)](#)

Clase para conexión a servidores SQL Server, con uso de métodos claros y fáciles de conexión.
Esta clase es una alternativa a la conexión por medio de las clases predeterminadas de Laravel.
Puedo Implementarse en PHP Estructurado, el unico metodo que no se podria usar seria `->collect()`
Clase liviana y precisa para ejecutar cualquier tipo de sentencia en la base de datos.

## Instalación
# Instalaciòn a través de Composer

```sh
composer require rmunate/sql-server-lite
```
Importante: El Driver ODBC >= 17 debe Estar Instalado en la Maquina.
https://learn.microsoft.com/es-es/sql/connect/odbc/download-odbc-driver-for-sql-server

# Para llamado en los controladores, invoque el uso.

```sh
use Rmunate\SqlServerLite\SQLServer;
```

# Cree un Helper que llame los valores de la conexion los cuales deben estar creados en el ENV del proyecto 
Valide en la documentacion de laravel como crear sus Helpers Personalizados, y garantice que dentro del Composer.json, se encuentre para iniciar el archivo con el Autoload.
```sh

#En este caso se usará un archivo llamado Helpers.php que estará en la ubicacion app/Http, y dentro del archivo coposer.json se aplicará la siguiente configuración.
"autoload-dev": {
    "psr-4": {
        "Tests\\": "tests/"
    },
    "files":["app/Http/Helpers.php"]
},
```

El valor "instance" solo debe ir en los casos donde la conexión requiere una instancia especifica, de lo contrario no incluirlo en el helper.

# Funcion en los Helpers 
```sh
function CREDENCIALES(){
    $credenciales = array(
        'server'   => env('DB_SQLSVR_NAME'),
        'instance' => env('DB_SQLSVR_INSTANCE'), #No es Obligatoria, no poner en caso de no tener instancia
        'database' => env('DB_SQLSVR_DATABASE'),
        'user'     => env('DB_SQLSVR_USER'),
        'password' => env('DB_SQLSVR_PASS')
    );
    return $credenciales;
}
```

# Variables en el ENV.
```sh
DB_SQLSVR_NAME="10.25.21.170"
DB_SQLSVR_INSTANCE="PROD" #No es Obligatoria, no poner en caso de no tener instancia
DB_SQLSVR_DATABASE="basededatos"
DB_SQLSVR_USER="usuario"
DB_SQLSVR_PASS="contraseña"
```



# Genere la conexion a la base de datos de la siguiente forma.
```sh
SQLServer::database(CREDENCIALES())
```

# Comandos con retorno de Datos #

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->select("SELECT * FROM 'usuarios'")->get()` | Ejecute las consultas que requiera a la base de datos y genere la respuesta con el metodo ->get(), usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->get()` | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta con el metodo ->get(), usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->noCount()->procedure("EXEC procedure")->get()` | El metodo noCount puede ser necesario en consultas a procedimientos que contangan OpenQuerys o SubQuerys |


# Comandos Adicionales con retorno de Datos #

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->first()` | Trae el primer valor del arreglo retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->last()` | Trae el ultimo valor del arreglo retornado en la Consulta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->count()` | Cuenta el total de los registros de Respuesta. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->reverse()` | Retorna los valores de respuesta en Reversa. |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->collect()` | Retorna la respuesta como colección de Laravel. |

# Comandos con retorno Booleano true o false (Rollback y Commit Implicito)#

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| `SQLServer::database(CREDENCIALES())->update("UPDATE 'usuarios' SET 'nombre' = 'Raul' WHERE 'id' = 1")` | Actualice los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->insert("INSERT INTO 'users' ('id', 'name') VALUES (1, 'Administrador')")` | Inserte los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->delete("DELETE FROM 'users' WHERE 'id'=1")` | Elimine los datos que requiera en la base de datos, usando sentencias propias de SQL |
| `SQLServer::database(CREDENCIALES())->procedure("EXEC procedure", false)` | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta True o False (Procedimientos Sin Retorno De Datos.) |
| `SQLServer::database(CREDENCIALES())->check(["tabla1","tabla2",...])->delete("DELETE FROM 'users' WHERE 'id'=1")` | El metodo `->check()` valida y ejecuta todas las restricciones de llaves foraneas de la tabla o de las tablas ingresadas en el metodo, estas deben ir siempre dentro de un arreglo como se muestra en el ejemplo. |
| `SQLServer::database(CREDENCIALES())->nocheck(["tabla1","tabla2"])->delete("DELETE FROM 'users' WHERE 'id'=1")` | El metodo `->check()` deshabilita las restricciones de llaves foraneas de la tabla o de las tablas ingresadas en el metodo, estas deben ir siempre dentro de un arreglo como se muestra en el ejemplo. |

## Desarrollador
- Ingeniero, Raúl Mauricio Uñate Castro
- raulmauriciounate@gmail.com
