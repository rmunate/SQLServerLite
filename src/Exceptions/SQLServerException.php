<?php

namespace Rmunate\SqlServerLite\Exceptions;

use Exception;

class SQLServerException extends Exception
{
    public static function create(string $message)
    {
        return new self("\Rmunate\SqlServerLite\SQLServer - Exception - {$message}");
    }

    
    
}