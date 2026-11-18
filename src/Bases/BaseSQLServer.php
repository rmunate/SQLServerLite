<?php

namespace Rmunate\SqlServerLite\Bases;

use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Response\SQLServerStatus;
use Rmunate\SqlServerLite\Validator\SQLServerValidator;

abstract class BaseSQLServer
{
    /**
     * Handle calls to missing methods on the helper.
     *
     * @param string $method     The name of the method being called.
     * @param array  $parameters The parameters passed to the method.
     *
     * @throws SQLServerException When the method does not exist.
     */
    public function __call($method, $parameters)
    {
        throw SQLServerException::create("The method '{$method}' does not exist");
    }

    /**
     * Create a new instance of the configuration using a connection configuration.
     *
     * @param string $connection The name of the connection.
     *
     * @return static The new configuration instance.
     */
    public static function connection(string $connection)
    {
        return new static(self::connectionCredentials($connection), $connection);
    }

    /**
     * Return the connection status.
     *
     * @param string $connection   The name of the connection.
     * @param int    $loginTimeout The login timeout duration.
     *
     * @return SQLServerStatus The connection status.
     */
    public static function status(string $connection, int $loginTimeout = 3)
    {
        $credentials = self::connectionCredentials($connection);

        try {
            $instance = new static($credentials, $connection, $loginTimeout);

            return new SQLServerStatus([
                'status'  => true,
                'message' => 'Connection Successful',
                'query'   => $instance,
            ]);
        } catch (\Throwable $th) {
            return new SQLServerStatus([
                'status'  => false,
                'message' => $th->getMessage(),
                'query'   => null,
            ]);
        }
    }

    private static function connectionCredentials(string $connection): object
    {
        $data = config("database.connections.{$connection}", []);

        if (!is_array($data)) {
            throw SQLServerException::create("The connection '{$connection}' must be an array in config/database.php.");
        }

        (new SQLServerValidator($data, $connection))->verify();

        return (object) $data;
    }
}
