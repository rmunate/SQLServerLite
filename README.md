# SQL-Server-PHP-Laravel
Alternativa de Conexión a SQL Server desde Laravel PHP

[![N|Solid](https://i.ibb.co/ZLzQTpm/Firma-Git-Hub.png)](#)

Clase para conexión a servidores SQL Server, con uso de métodos claros y fáciles de conexión.
Esta clase es una alternativa a la conexión por el ORM Eloquent.
Clase liviana y precisa para ejecutar cualquier tipo de sentencia en la base de datos.

## Instalación

# Instalaciòn por Composer

```sh
composer require rmunate/sql-server-lite
```

# Para llamado en los controladores, invoque el uso.

```sh
use Rmunate\SqlServerLite\SQLServer;
```

# Cree un Helper que llame los valores de la conexion los cuales deben estar creados en el ENV del proyecto 
Valide en la documentacion de laravel como crear sus Helpers Personalizados.
El valor "instance" solo debe ir en los casos donde la conexión requiere una instancia especifica, de lo contrario no incluirlo en el helper.

# Variables en el ENV.
```sh
DB_SQLSVR_NAME="10.25.21.170"
DB_SQLSVR_INSTANCE="PROD" //No es Obligatoria, no poner en caso de no tener instancia
DB_SQLSVR_DATABASE="basededatos"
DB_SQLSVR_USER="usuario"
DB_SQLSVR_PASS="contraseña"
```

# Funcion en los Helpers 
```sh
function CREDENCIALES(){
    $credenciales = array(
        'server'   => env('DB_SQLSVR_NAME'),
        'instance' => env('DB_SQLSVR_INSTANCE'), //No es Obligatoria, no poner en caso de no tener instancia
        'database' => env('DB_SQLSVR_DATABASE'),
        'user'     => env('DB_SQLSVR_USER'),
        'password' => env('DB_SQLSVR_PASS')
    );
    return $credenciales;
}
```

# Genere la conexion a la base de datos de la siguiente forma.
```sh
SQLServer::database(CREDENCIALES())
```

# Comandos con retorno de Datos #

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| SQLServer::database(CREDENCIALES())->select("SELECT * FROM 'usuarios'")->get() | Ejecute las consultas que requiera a la base de datos y genere la respuesta con el metodo ->get(), usando sentencias propias de SQL |
| SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->get() | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta con el metodo ->get(), usando sentencias propias de SQL |
| SQLServer::database(CREDENCIALES())->noCount()->procedure("EXEC procedure")->get() | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta con el metodo ->get(), usando sentencias propias de SQL , El metodo noCount puede ser necesario en consultas a procedimientos que contangan OpenQuerys |


# Comandos Adicionales con retorno de Datos #

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->first() | Trae el primer valor (TOP 1) |
| SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->last() | Trae el ultimo valor (TOP 1) |
| SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->count() | Cuenta el total de los registros de Respuesta |
| SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->reverse() | Retorna los valores de respuesta en Reversa |
| SQLServer::database(CREDENCIALES())->procedure("EXEC procedure")->collect() | Retorna la respuesta como colección de Laravel |

# Comandos con retorno Booleano true o false #

| COMANDO | DESCRIPCIÓN |
| ----------- | ----------- |
| SQLServer::database(CREDENCIALES())->update("UPDATE 'usuarios' SET 'nombre' = 'Raul' WHERE 'id' = 1") | Actualice los datos que requiera en la base de datos, usando sentencias propias de SQL |
| SQLServer::database(CREDENCIALES())->insert("INSERT INTO 'users' ('id', 'name') VALUES (1, 'Administrador')") | Inserte los datos que requiera en la base de datos, usando sentencias propias de SQL |
| SQLServer::database(CREDENCIALES())->delete("DELETE FROM 'users' WHERE 'id'=1") | Inserte los datos que requiera en la base de datos, usando sentencias propias de SQL |
| SQLServer::database(CREDENCIALES())->procedure("EXEC procedure", false) | Ejecute los procedimientos almacenados en la base de datos y genere la respuesta True o False |


## Desarrollador

Ingeniero, Raúl Mauricio Uñate Castro
raulmauriciounate@gmail.com
