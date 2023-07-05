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

namespace Rmunate\SqlServerLite\Exceptions;

class Messages
{
    const MANUAL_URL = 'https://github.com/rmunate/SQLServerLite';
    const LIBRARY_NAME = 'SQLServerLite';

    /**
     * Returns the exception message for a non-existent key in an array.
     *
     * @param string $key The key that is not present or empty in the array.
     *
     * @return string The formatted exception message.
     */
    public static function nonExistentKeyException($key): string
    {
        return self::formatExceptionMessage(
            'SQLServer::database()',
            "The key [$key] is not present or empty in the array passed."
        );
    }

    /**
     * Returns the exception message for an undefined environment variable.
     *
     * @return string The formatted exception message.
     */
    public static function undefinedEnvironmentVariableException(): string
    {
        return self::formatExceptionMessage(
            'SQLServer::env()',
            'The environment variables do not exist or are poorly defined, they must have {PREFIX}_SQLSRV_IDENTIFIER.'
        );
    }

    /**
     * Returns the exception message for working outside of Laravel.
     *
     * @return string The formatted exception message.
     */
    public static function outsideOfLaravel(): string
    {
        return self::formatExceptionMessage(
            'SQLServer::connection()',
            'You are not working on a Laravel application. This method is only functional in that framework.'
        );
    }

    /**
     * Returns the exception message for a non-existent key in Laravel configuration.
     *
     * @param string $key The key that is not present or empty in the Laravel configuration.
     *
     * @return string The formatted exception message.
     */
    public static function nonExistentKeyLaravelException($key): string
    {
        return self::formatExceptionMessage(
            'SQLServer::connection()',
            "The key [$key] is not present or empty in [config/database.php]."
        );
    }

    /**
     * Returns the exception message for an error disabling foreign keys.
     *
     * @param string|null $message Additional error message (optional).
     *
     * @return string The formatted exception message.
     */
    public static function disableForeignKeysException($message = null): string
    {
        return self::formatExceptionMessage(
            'SQLServer::disableForeignKeys()',
            $message ?? 'Error disabling foreign keys'
        );
    }

    /**
     * Returns the exception message for an error enabling foreign keys.
     *
     * @param string|null $message Additional error message (optional).
     *
     * @return string The formatted exception message.
     */
    public static function enableForeignKeysException($message = null): string
    {
        return self::formatExceptionMessage(
            'SQLServer::enableForeignKeys()',
            $message ?? 'Error enabling foreign keys'
        );
    }

    /**
     * Returns the exception message for a non-SELECT query.
     *
     * @return string The formatted exception message.
     */
    public static function notIsSelectQueryException(): string
    {
        return self::formatExceptionMessage(
            'SQLServer::select()',
            'The string sent does not start with SELECT, this is required.'
        );
    }

    /**
     * Returns the exception message for a non-UPDATE query.
     *
     * @return string The formatted exception message.
     */
    public static function notIsUpdateQueryException(): string
    {
        return self::formatExceptionMessage(
            'SQLServer::update()',
            'The string sent does not start with UPDATE, this is required.'
        );
    }

    /**
     * Returns the exception message for a non-INSERT query.
     *
     * @return string The formatted exception message.
     */
    public static function notIsInsertQueryException(): string
    {
        return self::formatExceptionMessage(
            'SQLServer::insert()',
            'The string sent does not start with INSERT, this is required.'
        );
    }

    /**
     * Returns the exception message for a non-DELETE query.
     *
     * @return string The formatted exception message.
     */
    public static function notIsDeleteQueryException(): string
    {
        return self::formatExceptionMessage(
            'SQLServer::delete()',
            'The string sent does not start with DELETE, this is required.'
        );
    }

    /**
     * Returns the exception message for a non-EXEC procedure.
     *
     * @return string The formatted exception message.
     */
    public static function notIsProcedureException(): string
    {
        return self::formatExceptionMessage(
            'SQLServer::executeProcedure/executeTransactionalProcedure()',
            'The string sent does not start with EXEC, this is required.'
        );
    }

    /**
     * Returns the exception message for a SELECT query error.
     *
     * @param string|null $message Additional error message (optional).
     *
     * @return string The formatted exception message.
     */
    public static function selectException($message = null): string
    {
        return self::formatExceptionMessage(
            'SQLServer::select()',
            $message ?? 'Error Select Query'
        );
    }

    /**
     * Returns the exception message for an UPDATE query error.
     *
     * @param string|null $message Additional error message (optional).
     *
     * @return string The formatted exception message.
     */
    public static function updateException($message = null): string
    {
        return self::formatExceptionMessage(
            'SQLServer::update()',
            $message ?? 'Error Update Sentence'
        );
    }

    /**
     * Returns the exception message for an INSERT query error.
     *
     * @param string|null $message Additional error message (optional).
     *
     * @return string The formatted exception message.
     */
    public static function insertException($message = null): string
    {
        return self::formatExceptionMessage(
            'SQLServer::insert()',
            $message ?? 'Error Insert Sentence'
        );
    }

    /**
     * Returns the exception message for a DELETE query error.
     *
     * @param string|null $message Additional error message (optional).
     *
     * @return string The formatted exception message.
     */
    public static function deleteException($message = null): string
    {
        return self::formatExceptionMessage(
            'SQLServer::delete()',
            $message ?? 'Error Delete Sentence'
        );
    }

    /**
     * Returns the exception message for a stored procedure execution error.
     *
     * @param string|null $message Additional error message (optional).
     *
     * @return string The formatted exception message.
     */
    public static function executeProcedureException($message = null): string
    {
        return self::formatExceptionMessage(
            'SQLServer::executeProcedure/executeTransactionalProcedure()',
            $message ?? 'Error Store Procedure Sentence'
        );
    }

    /**
     * Formats the exception message.
     *
     * @param string $method  The method that triggered the exception.
     * @param string $message The exception message.
     *
     * @return string The formatted exception message.
     */
    public static function formatExceptionMessage(string $method = '', $message): string
    {
        $formattedMessage = sprintf(
            '%s Exception in the method %s - %s %s',
            self::LIBRARY_NAME,
            $method,
            $message,
            self::MANUAL_URL
        );

        return $formattedMessage;
    }
}
