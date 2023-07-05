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

namespace Rmunate\SqlServerLite\Bases;

use BadMethodCallException;

abstract class BaseSQLServer
{
    /**
     * Handle calls to missing methods on the helper.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }

    /**
     * Create a new instance of the configuration using a database configuration.
     *
     * @param array $database The database configuration array.
     *
     * @return static The new configuration instance.
     */
    public static function database(array $database)
    {
        return new static($database, null, null);
    }

    /**
     * Create a new instance of the configuration using an environment variable prefix.
     *
     * @param array $envPrefix The environment variable prefix array.
     *
     * @return static The new configuration instance.
     */
    public static function env(array $envPrefix)
    {
        return new static(null, $envPrefix, null);
    }

    /**
     * Create a new instance of the configuration using a connection configuration.
     *
     * @param array $connection The connection configuration array.
     *
     * @return static The new configuration instance.
     */
    public static function connection(array $connection)
    {
        return new static(null, null, $connection);
    }

    /**
     * Return the connection status.
     *
     * @return object
     */
    abstract public function status(): object;

    /**
     * Executes a SELECT query and returns the result set.
     *
     * @param string $statement The SELECT query statement.
     * @param array  $params    The optional parameters for prepared statements.
     *
     * @throws \Exception If the query is not a SELECT query or if there is an error executing the query.
     *
     * @return array The result set as an array of associative arrays.
     */
    abstract public function select(string $statement, array $params = []): static;

    /**
     * Execute an UPDATE query.
     *
     * @param string $statement The UPDATE query statement.
     * @param array  $params    The array of parameters for the prepared statement.
     *
     * @throws Exception If there is an error executing the SQL query.
     *
     * @return bool Returns true if the query is executed successfully, false otherwise.
     */
    abstract public function update(string $statement, array $params = []): bool;

    /**
     * Execute an INSERT query.
     *
     * @param string $statement The INSERT query statement.
     * @param array  $params    The array of parameters for the prepared statement.
     *
     * @throws \Exception If there is an error executing the SQL query.
     *
     * @return bool Returns true if the INSERT query was successful, false otherwise.
     */
    abstract public function insert(string $statement, array $params = []): bool;

    /**
     * Execute a DELETE query.
     *
     * @param string $statement The DELETE query statement.
     * @param array  $params    The array of parameters for the prepared statement.
     *
     * @throws \Exception If there is an error executing the SQL query.
     *
     * @return bool Returns true if the DELETE query was successful, false otherwise.
     */
    abstract public function delete(string $statement, array $params = []): bool;

    /**
     * Ejecuta un procedimiento almacenado y devuelve el resultado.
     *
     * @param string $procedure El nombre del procedimiento almacenado.
     * @param array  $params    Los parámetros para el procedimiento almacenado (opcional).
     *
     * @throws \Exception Si hay un error al ejecutar el procedimiento almacenado.
     *
     * @return array El resultado del procedimiento almacenado como un arreglo asociativo.
     */
    abstract public function executeProcedure(string $procedure): static;

    /**
     * Ejecuta un procedimiento almacenado para transacciones y devuelve un valor booleano.
     *
     * @param string $procedure El nombre del procedimiento almacenado.
     * @param array  $params    Los parámetros para el procedimiento almacenado (opcional).
     *
     * @throws \Exception Si hay un error al ejecutar el procedimiento almacenado.
     *
     * @return bool True si el procedimiento almacenado se ejecutó correctamente, false en caso contrario.
     */
    abstract public function executeTransactionalProcedure(string $procedure): bool;

    /**
     * Return the first element of the response.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @return mixed|null The first element of the response or null if the response is empty
     */
    abstract public function first(string $type = 'array'): mixed;

    /**
     * Return the last element of the response as an object.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @return mixed|null The last element of the response as an object or null if the response is empty or not an array
     */
    abstract public function last(string $type = 'array'): mixed;

    /**
     * Return instance collect.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @throws \Exception If called outside of Laravel
     *
     * @return mixed
     */
    abstract public function collect(string $type = 'array'): mixed;

    /**
     * Return final query.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @return mixed
     */
    abstract public function get(string $type = 'array'): mixed;

    /**
     * Return the count of elements in the response.
     *
     * @return int The count of elements in the response
     */
    abstract public function count(): int;
}
