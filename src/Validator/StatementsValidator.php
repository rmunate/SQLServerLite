<?php

namespace Rmunate\SqlServerLite\Validator;

use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Utilities\Utilities;

/**
 * Class StatementsValidator.
 */
class StatementsValidator
{
    public static function withoutComments(string $statement)
    {
        $statement = preg_replace('/(--|\/\/).*$/m', '', $statement);
        $statement = preg_replace('/\/\*.*?\*\//s', '', $statement);

        foreach (["/*", "*/", "//", "--", "<-", "->"] as $match) {
            if (stripos($statement, $match)) {
                throw SQLServerException::create('The statement you are trying to execute contains comments; please remove them to proceed.');
            }
        }

        return $statement;
    }

    /**
     * Verify if the query is a SELECT query.
     *
     * @param string $statement SQL statement to verify.
     *
     * @throws SQLServerException Throws an exception if the statement is not a SELECT query.
     */
    public static function isSelect(string $statement)
    {
        $statement = trim($statement);
        $statement = self::withoutComments($statement);

        if (!stripos($statement, 'SELECT') === 0) {
            throw SQLServerException::create('Invalid SELECT statement. Please provide a valid SELECT query.');
        }
    }

    /**
     * Verify if the query is an UPDATE query.
     *
     * @param string $statement SQL statement to verify.
     *
     * @throws SQLServerException Throws an exception if the statement is not an UPDATE query.
     */
    public static function isUpdate(string $statement)
    {
        $statement = trim($statement);
        $statement = self::withoutComments($statement);

        if (!stripos($statement, 'UPDATE') === 0) {
            throw SQLServerException::create('Invalid UPDATE statement. Please provide a valid UPDATE query.');
        }
    }

    /**
     * Verify if the query is an INSERT query.
     *
     * @param string $statement SQL statement to verify.
     *
     * @throws SQLServerException Throws an exception if the statement is not an INSERT query.
     */
    public static function isInsert(string $statement)
    {
        $statement = trim($statement);
        $statement = self::withoutComments($statement);

        if (!stripos($statement, 'INSERT') === 0) {
            throw SQLServerException::create('Invalid INSERT statement. Please provide a valid INSERT query.');
        }
    }

    /**
     * Verify if the query is a DELETE query.
     *
     * @param string $statement SQL statement to verify.
     *
     * @throws SQLServerException Throws an exception if the statement is not a DELETE query.
     */
    public static function isDelete(string $statement)
    {
        $statement = trim($statement);
        $statement = self::withoutComments($statement);

        if (!stripos($statement, 'DELETE') === 0) {
            throw SQLServerException::create('Invalid DELETE statement. Please provide a valid DELETE query.');
        }
    }

    /**
     * Verify if the query is a stored procedure query.
     *
     * @param string $statement SQL statement to verify.
     *
     * @throws SQLServerException Throws an exception if the statement is not a stored procedure query.
     */
    public static function isProcedure(string $statement)
    {
        $statement = trim($statement);
        $statement = self::withoutComments($statement);

        if (!stripos($statement, 'EXEC') === 0) {
            throw SQLServerException::create('Invalid PROCEDURE statement. Please provide a valid PROCEDURE query.');
        }
    }

    /**
     * Verify if the parameters are an array with multiple parameters.
     *
     * @param array $params Array of parameters to verify.
     *
     * @throws SQLServerException Throws an exception if the parameters are not valid.
     */
    public static function isValidParams(array $params)
    {
        if (Utilities::hasSubArrays($params)) {
            throw SQLServerException::create('Arrays with multiple parameters can only be used for data insertion processes.');
        }
    }
}
