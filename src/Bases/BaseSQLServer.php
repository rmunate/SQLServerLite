<?php

namespace Rmunate\SqlServerLite\Bases;

use BadMethodCallException;

abstract class BaseSQLServer
{
    /**
     * Handle calls to missing methods on the helper.
     *
     * @param string $method     The name of the method being called.
     * @param array  $parameters The parameters passed to the method.
     *
     * @throws BadMethodCallException When the method does not exist.
     *
     * @return mixed The return type is not specified and can be any type.
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
     * @param string $envPrefix The environment variable prefix.
     *
     * @return static The new configuration instance.
     */
    public static function env(string $envPrefix)
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
    public static function connection(string $connection)
    {
        return new static(null, null, $connection);
    }

    /**
     * Return the connection status.
     *
     * @return object The connection status object.
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
    abstract public function select(string $statement, array $params = []);

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
     * Execute an INSERT query and return the last inserted ID.
     *
     * @param string $statement The INSERT query statement.
     * @param array  $params    The array of parameters for the prepared statement.
     *
     * @throws \Exception If there is an error executing the SQL query.
     *
     * @return mixed The last inserted ID.
     */
    abstract public function insertGetId(string $statement, array $params = []);

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
     * Execute a stored procedure and return the result.
     *
     * @param string $procedure The name of the stored procedure.
     * @param array  $params    The parameters for the stored procedure (optional).
     *
     * @throws \Exception If there is an error executing the stored procedure.
     *
     * @return array The result of the stored procedure as an associative array.
     */
    abstract public function executeProcedure(string $procedure, array $params = []);

    /**
     * Execute a stored procedure for transactions and return a boolean value.
     *
     * @param string $procedure The name of the stored procedure.
     * @param array  $params    The parameters for the stored procedure (optional).
     *
     * @throws \Exception If there is an error executing the stored procedure.
     *
     * @return bool True if the stored procedure was executed successfully, false otherwise.
     */
    abstract public function executeTransactionalProcedure(string $procedure, array $params = []): bool;

    /**
     * Return the first element of the response.
     *
     * @param string $type The type of data to return ("object" or "array").
     *
     * @return mixed|null The first element of the response or null if the response is empty.
     */
    abstract public function first(string $type = 'array'): mixed;

    /**
     * Return the last element of the response as an object.
     *
     * @param string $type The type of data to return ("object" or "array").
     *
     * @return mixed|null The last element of the response as an object or null if the response is empty or not an array.
     */
    abstract public function last(string $type = 'array'): mixed;

    /**
     * Return a collection instance.
     *
     * @param string $type The type of data to return ("object" or "array").
     *
     * @throws \Exception If called outside of Laravel.
     *
     * @return mixed The collection instance.
     */
    abstract public function collect(string $type = 'array'): mixed;

    /**
     * Return the final query.
     *
     * @param string $type The type of data to return ("object" or "array").
     *
     * @return mixed The final query result.
     */
    abstract public function get(string $type = 'array'): mixed;

    /**
     * Return the count of elements in the response.
     *
     * @return int The count of elements in the response.
     */
    abstract public function count(): int;
}
