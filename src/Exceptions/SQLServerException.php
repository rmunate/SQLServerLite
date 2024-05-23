<?php

namespace Rmunate\SqlServerLite\Exceptions;

use Exception;

class SQLServerException extends Exception
{
    /**
     * Create a new SQLServerException instance with a personalized message.
     *
     * @param string $message The exception message.
     *
     * @return SQLServerException
     */
    public static function create(string $message)
    {
        return new self("Rmunate\\SqlServerLite\\SQLServer - Exception - {$message}");
    }
}
