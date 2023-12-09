<?php

namespace Rmunate\SqlServerLite\Exceptions;

use Exception;

class SQLServerException extends Exception
{
    /**
     * Return a exception personalized message
     * @param string $message
     * 
     * @return Exception
     */
    public static function create(string $message)
    {
        return new self("\Rmunate\SqlServerLite\SQLServer - Exception - {$message}");
    }
}
