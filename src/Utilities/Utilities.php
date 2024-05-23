<?php

namespace Rmunate\SqlServerLite\Utilities;

use Rmunate\SqlServerLite\Exceptions\SQLServerException;

class Utilities
{
    /**
     * Return true if the params has sub arrays.
     *
     * @param mixed $array
     *
     * @return bool
     */
    public static function hasSubArrays($array)
    {
        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $elemento) {
            if (is_array($elemento)) {
                return true;
            }
        }

        return false;
    }

    /**
     * return the table name for disable constraint.
     *
     * @param string $statement
     *
     * @return string
     */
    public static function getNameTable(string $statement)
    {
        if (stripos($statement, 'UPDATE') === 0 || stripos($statement, 'INSERT') === 0 || stripos($statement, 'DELETE') === 0) {
            $extractQuery = substr(trim($statement), 6);

            return explode(' ', trim($extractQuery))[0];
        }

        throw SQLServerException::create('It is not identified which table to disable foreign key checking, consider supplying it directly in the method.');
    }

    /**
     * Sanitize a SQL statement by removing extra whitespace, tabs, and line breaks.
     *
     * @param string $statement The SQL statement to be sanitized.
     *
     * @return string The sanitized SQL statement.
     */
    public static function statementSanitize(string $statement): string
    {
        // Trim and remove extra whitespace, tabs, and line breaks from the SQL statement.
        return trim(str_replace(["\r", "\n", "\t"], '', $statement));
    }

    /**
     * Check if the statement is unprepared, i.e., it does not contain named parameters
     * or it contains named parameters and they are not empty.
     *
     * @param string $statement The SQL statement to be checked.
     * @param array  $params    An array of named parameters.
     *
     * @return bool Returns true if the statement is unprepared, false otherwise.
     */
    public static function unpreparedQuery(string $statement, array $params = []): bool
    {
        /**
         * Check if the statement does not contain named parameters or it contains named parameters and they are not empty.
         *
         * @phpstan-ignore-next-line */
        return (strpos($statement, ':') === false) || (strpos($statement, ':') !== false && !empty($params));
    }

    /**
     * Check if a given value exists in an array.
     *
     * @param mixed $value The value to search for in the array.
     * @param array $array The array to search within.
     *
     * @return bool Returns true if the value is found in the array, false otherwise.
     */
    public static function inArray($value, array $array): bool
    {
        // Use the in_array function to check if the value exists in the array.
        return in_array($value, $array);
    }
}
