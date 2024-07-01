<?php

namespace Rmunate\SqlServerLite\Validator;

use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Utilities\Utilities;

/**
 * Class StatementsValidator.
 */
class StatementsValidator
{
    /**
     * Remove comments from a SQL statement to prevent execution issues.
     *
     * @param string $statement The SQL statement to remove comments from.
     *
     * @throws SQLServerException If the statement contains comments, an exception is thrown.
     *
     * @return string The SQL statement without comments.
     */
    public static function withoutComments(string $statement)
    {
        // Remove single-line comments (// or --)
        $statement = preg_replace('/(--|\/\/).*$/m', '', $statement);

        // Remove multi-line comments (/* ... */)
        $statement = preg_replace('/\/\*.*?\*\//s', '', $statement);

        // Check for forbidden comment strings
        $forbiddenCommentPatterns = ['/*', '*/', '//', '--', '<-', '->'];
        foreach ($forbiddenCommentPatterns as $commentPattern) {
            if (stripos($statement, $commentPattern)) {
                throw SQLServerException::create("The Statement Contains Comments ($commentPattern); Please Remove Them To Proceed.");
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
            throw SQLServerException::create('Invalid Select Statement. Please Provide A Valid Select Query.');
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
            throw SQLServerException::create('Invalid Update Statement. Please Provide A Valid Update Query.');
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
            throw SQLServerException::create('Invalid Insert Statement. Please Provide A Valid Insert Query.');
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
            throw SQLServerException::create('Invalid Delete Statement. Please Provide A Valid Delete Query.');
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
            throw SQLServerException::create('Invalid Procedure Statement. Please Provide A Valid Procedure Query.');
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
            throw SQLServerException::create('Arrays With Multiple Parameters Can Only Be Used For Data Insertion Processes.');
        }
    }
}
