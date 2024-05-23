<?php

namespace Rmunate\SqlServerLite\Singleton;

use PDO;
use PDOException;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;

class SQLServerSingleton
{
    /**
     * @var array|null Array to store instances of the database connection.
     */
    private static $instance = null;

    /**
     * @var bool Flag to indicate whether to begin a transaction automatically.
     */
    private static $beginTransaction = false;

    /**
     * Establish the database connection.
     *
     * @param mixed  $credentials  The database credentials.
     * @param string $connection   The name of the connection.
     * @param int    $loginTimeout The login timeout duration.
     *
     * @return PDO The PDO database connection instance.
     */
    public static function mount($credentials, string $connection, int $loginTimeout)
    {
        if (empty(self::$instance[$connection])) {
            try {
                // Create the DSN:
                $dsn = "sqlsrv:Server={$credentials->host}";
                $dsn .= (isset($credentials->port) && !empty($credentials->port)) ? ",{$credentials->port}" : ',1433';
                $dsn .= (isset($credentials->instance) && !empty($credentials->instance)) ? "\\{$credentials->instance}" : '';
                $dsn .= ";Database={$credentials->database};LoginTimeout={$loginTimeout}";

                // Create the PDO connection.
                $conection = new PDO($dsn, $credentials->username, $credentials->password, [
                    PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
                    PDO::SQLSRV_ATTR_QUERY_TIMEOUT => 0,
                    PDO::SQLSRV_ATTR_ENCODING      => PDO::SQLSRV_ENCODING_UTF8,
                ]);

                $conection->setAttribute(PDO::SQLSRV_ATTR_ENCODING, constant('PDO::SQLSRV_ENCODING_'.mb_strtoupper($credentials->charset ?? 'utf8')));

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

    /**
     * Set property to not automatically save changes.
     *
     * @return void
     */
    public static function beginTransaction()
    {
        if (!empty(self::$instance)) {
            foreach (self::$instance as $key => $instance) {
                $instance->beginTransaction();
            }
        }

        self::$beginTransaction = true;
    }

    /**
     * Save changes.
     *
     * @return void
     */
    public static function commit()
    {
        if (!empty(self::$instance)) {

            foreach (self::$instance as $key => $instance) {
                $instance->commit();
            }

            self::$beginTransaction = false;

        } else {

            throw SQLServerException::create('The connection to the database is not initialized.');

        }
    }

    /**
     * Do not save changes.
     *
     * @return void
     */
    public static function rollback()
    {
        if (!empty(self::$instance)) {

            foreach (self::$instance as $key => $instance) {
                $instance->rollBack();
            }

            self::$beginTransaction = false;

        } else {

            throw SQLServerException::create('The connection to the database is not initialized.');

        }
    }
}
