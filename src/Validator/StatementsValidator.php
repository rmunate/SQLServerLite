<?php

namespace Rmunate\SqlServerLite\Validator;

use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Utilities\Utilities;

class StatementsValidator
{
    public static function isSelect(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'SELECT') === 0) {
            throw SQLServerException::create('Invalid SELECT statement. Please provide a valid SELECT query.');
        }
    }

    public static function isUpdate(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'UPDATE') === 0) {
            throw SQLServerException::create('Invalid UPDATE statement. Please provide a valid UPDATE query.');
        }
    }

    public static function isInsert(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'INSERT') === 0) {
            throw SQLServerException::create('Invalid INSERT statement. Please provide a valid INSERT query.');
        }
    }

    public static function isDelete(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'DELETE') === 0) {
            throw SQLServerException::create('Invalid DELETE statement. Please provide a valid DELETE query.');
        }
    }

    public static function isProcedure(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'EXEC') === 0) {
            throw SQLServerException::create('Invalid PROCEDURE statement. Please provide a valid PROCEDURE query.');
        }
    }

    public static function isValidParams(array $params)
    {
        if (Utilities::hasSubArrays($params)) {
            throw SQLServerException::create('Los arreglos con multiples parametros solo pueden ser usados para procesos de insercion de datos.');
        }
    }
}
