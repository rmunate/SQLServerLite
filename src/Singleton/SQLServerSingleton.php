<?php

namespace Rmunate\SqlServerLite\Singleton;

use PDO;
use PDOException;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;

class SQLServerSingleton {

    private static $instance = null;
    private static $beginTransaction = false;

    public static function mount($credentials, string $connection, int $loginTimeout) {

        if (empty(self::$instance[$connection])) {

            try{

                //Crear DSN:
                $dsn = "sqlsrv:Server={$credentials->host}";
                $dsn .= (isset($credentials->port) && !empty($credentials->port)) ? ",{$credentials->port}" : ",1433";
                $dsn .= (isset($credentials->instance) && !empty($credentials->instance)) ? "\\{$credentials->instance}" : "";
                $dsn .= ";Database={$credentials->database};LoginTimeout={$loginTimeout}";
                
                //Crear conexion PDO
                $conection = new PDO($dsn, $credentials->username, $credentials->password, [
                                PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
                                PDO::SQLSRV_ATTR_QUERY_TIMEOUT => 0,
                                PDO::SQLSRV_ATTR_ENCODING      => PDO::SQLSRV_ENCODING_UTF8,
                            ]);

                $conection->setAttribute(PDO::SQLSRV_ATTR_ENCODING, constant('PDO::SQLSRV_ENCODING_' . mb_strtoupper($credentials->charset ?? "utf8")));

                if (self::$beginTransaction) {
                    $conection->beginTransaction();
                }

                self::$instance[$connection] = $conection;

            } catch (PDOException $e) {
                
                throw SQLServerException::create($e->getMessage());
            }

        }

        return self::$instance[$connection];
    }

    public static function beginTransaction() {

        if (empty(self::$instance)) {
            self::$beginTransaction = true;
        } else {
            foreach (self::$instance as $key => $instance) {
                $instance->beginTransaction();
            }
        }
    }

    public static function commit() {
        if (!empty(self::$instance)) {
            foreach (self::$instance as $key => $instance) {
                $instance->commit();
            }
            self::$beginTransaction = false;
        } else {
            throw SQLServerException::create("La conexion a la base de datos no esta inicializada.");
        }
    }

    public static function rollback() {
        if (!empty(self::$instance)) {
            foreach (self::$instance as $key => $instance) {
                $instance->rollBack();
            }
            self::$beginTransaction = false;
        } else {
            throw SQLServerException::create("La conexion a la base de datos no esta inicializada.");
        }
    }

}