<?php

/*
 * Copyright (c) [2023] [RAUL MAURICIO UÑATE CASTRO]
 *
 * This library is open source software licensed under the MIT license.
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this library and associated
 * documentation files (the "Software"), to deal in the library without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the library,
 * and to permit persons to whom the library is furnished to do so, subject to the following conditions:
 *
 * - Use the library for commercial or non-commercial purposes.
 * - Modify the library and adapt it to your own needs.
 * - Distribute copies of the library.
 * - Sublicense the library.
 *
 * When using or distributing this library, it is required to include the following attribution in all copies or
 * substantial portions of the library:
 *
 * "[RAUL MAURICIO UÑATE CASTRO], the copyright holder of this library, must
 * be acknowledged and mentioned in all copies or derivatives of the library."
 *
 * In addition, if modifications are made to the library, it is requested to include an additional note in the
 * documentation or in any other means of notifying the changes made, stating:
 *
 * "This library has been modified from the original library developed by [RAUL MAURICIO UÑATE CASTRO]."
 *
 * THE LIBRARY IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE LIBRARY OR THE USE OR OTHER DEALINGS IN THE LIBRARY.
 */

namespace Rmunate\SqlServerLite\Traits;

use PDO;

/**
 * This trait provides common utility functions.
 */
trait CommonFunctions
{
    /**
     * Checks if the code is running within a Laravel application.
     *
     * @return bool Returns true if running within a Laravel application, false otherwise.
     */
    public function inLaravel(): bool
    {
        return class_exists('Illuminate\Foundation\Application');
    }

    /**
     * Checks if the response is not empty and is an array.
     *
     * @return bool Returns true if the response is not empty and is an array, false otherwise.
     */
    public function isNonEmptyArray(): bool
    {
        return !empty($this->response) && is_array($this->response);
    }

    /**
     * Converts an array or stdClass object to a stdClass object.
     *
     * @param array|object $data The array or stdClass object to convert.
     *
     * @return object The converted stdClass object.
     */
    public function toObject($data) 
    {
        return json_decode(json_encode($data), false);
    }

    /**
     * Checks if the query is a SELECT query.
     *
     * @param string $statement The query statement.
     *
     * @return bool Returns true if it is a SELECT query, false otherwise.
     */
    public function isSelectQuery(string $statement): bool
    {
        // Use the stripos function to check if the query statement starts with "SELECT".
        // Returns true if it is a SELECT query, and false otherwise.
        return stripos($statement, 'SELECT') !== 0;
    }

    /**
     * Checks if the query is a UPDATE query.
     *
     * @param string $statement The query statement.
     *
     * @return bool Returns true if it is a SELECT query, false otherwise.
     */
    public function isUpdateQuery(string $statement): bool
    {
        // Use the stripos function to check if the query statement starts with "UPDATE".
        // Returns true if it is a UPDATE query, and false otherwise.
        return stripos($statement, 'UPDATE') !== 0;
    }

    /**
     * Checks if the query is an INSERT query.
     *
     * @param string $statement The query statement.
     *
     * @return bool Returns true if it is an INSERT query, false otherwise.
     */
    public function isInsertQuery(string $statement): bool
    {
        // Use the stripos function to check if the query statement starts with "INSERT".
        // Returns true if it is an INSERT query, and false otherwise.
        return stripos($statement, 'INSERT') === 0;
    }

    /**
     * Checks if the query is a DELETE query.
     *
     * @param string $statement The query statement.
     *
     * @return bool Returns true if it is a DELETE query, false otherwise.
     */
    public function isDeleteQuery(string $statement): bool
    {
        // Use the stripos function to check if the query statement starts with "DELETE".
        // Returns true if it is a DELETE query, and false otherwise.
        return stripos($statement, 'DELETE') === 0;
    }

    /**
     * Checks if the query is a stored procedure call.
     *
     * @param string $statement The query statement.
     *
     * @return bool Returns true if it is a stored procedure call, false otherwise.
     */
    public function isStoredProcedure(string $statement): bool
    {
        // Use the stripos function to check if the query statement starts with "EXEC".
        // Returns true if it is a stored procedure call, and false otherwise.
        return stripos($statement, 'EXEC') === 0;
    }

    /**
     * Check if a valid PDO connection to SQL Server exists.
     *
     * @return bool Returns true if a valid PDO connection exists, false otherwise.
     */
    public function hasValidPDOConnection(): bool
    {
        return (!is_null($this->PDO));
    }

}
