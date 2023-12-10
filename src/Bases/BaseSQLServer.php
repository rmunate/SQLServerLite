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
        $data = config("database.connections.{$connection}");

        (new SQLServerValidator($data, $connection))->verify();

        return new static((object) $data, $connection);
    }

    /**
     * Return the connection status.
     *
     * @param string $connection The name of the connection.
     * @param int    $loginTimeout The login timeout duration.
     * 
     * @return SQLServerStatus The connection status.
     */
    public static function status(string $connection, int $loginTimeout = 3)
    {
        $data = config("database.connections.{$connection}");

        (new SQLServerValidator($data, $connection))->verify();

        try {
            $instance = new static((object) $data, $connection, $loginTimeout);

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
}
