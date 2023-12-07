<?php

namespace Rmunate\SqlServerLite\Bases;

use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Response\SQLServerStatus;
use Rmunate\SqlServerLite\Validator\SQLServerValidator;

abstract class BaseSQLServer
{
    public function __call($method, $parameters)
    {
        throw SQLServerException::create("El metodo '{$method}' no existe");
    }

    public static function connection(string $connection)
    {
        $data = config("database.connections.{$connection}");

        (new SQLServerValidator($data, $connection))->verify();

        return new static((object) $data, $connection);
    }

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
