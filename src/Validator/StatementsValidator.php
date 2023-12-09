<?php

namespace Rmunate\SqlServerLite\Validator;

use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Utilities\Utilities;

class StatementsValidator
{
    /**
     * verify if the query is a select query
     * @param string $statement
     * 
     * @return Exception if does not a select query
     */
    public static function isSelect(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'SELECT') === 0) {
            throw SQLServerException::create('Invalid SELECT statement. Please provide a valid SELECT query.');
        }
    }

    /**
     * verify if the query is a update query
     * @param string $statement
     * 
     * @return Exception if does not a update query
     */
    public static function isUpdate(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'UPDATE') === 0) {
            throw SQLServerException::create('Invalid UPDATE statement. Please provide a valid UPDATE query.');
        }
    }

    /**
     * verify if the query is a insert query
     * @param string $statement
     * 
     * @return Exception if does not a insert query
     */
    public static function isInsert(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'INSERT') === 0) {
            throw SQLServerException::create('Invalid INSERT statement. Please provide a valid INSERT query.');
        }
    }

    /**
     * verify if the query is a delete query
     * @param string $statement
     * 
     * @return Exception if does not a delete query
     */
    public static function isDelete(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'DELETE') === 0) {
            throw SQLServerException::create('Invalid DELETE statement. Please provide a valid DELETE query.');
        }
    }

    /**
     * verify if the query is a storage procedure query
     * @param string $statement
     * 
     * @return Exception if does not a storage procedure query
     */
    public static function isProcedure(string $statement)
    {
        $statement = trim($statement);

        if (!stripos($statement, 'EXEC') === 0) {
            throw SQLServerException::create('Invalid PROCEDURE statement. Please provide a valid PROCEDURE query.');
        }
    }

    /**
     * verify if the params is an array with multiples params
     * @param string $params
     * 
     * @return Exception if does not a select query
     */
    public static function isValidParams(array $params)
    {
        if (Utilities::hasSubArrays($params)) {
            throw SQLServerException::create('Los arreglos con multiples parametros solo pueden ser usados para procesos de insercion de datos.');
        }
    }
}
