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

namespace Rmunate\SqlServerLite;

use PDO;
use Exception;
use Throwable;
use PDOException;
use Rmunate\SqlServerLite\SetCredentials;
use Rmunate\SqlServerLite\Traits\Methods;
use Rmunate\SqlServerLite\Traits\Attributes;
use Rmunate\SqlServerLite\Traits\Constraints;
use Rmunate\SqlServerLite\Traits\Transaction;
use Rmunate\SqlServerLite\Bases\BaseSQLServer;
use Rmunate\SqlServerLite\Exceptions\Messages;
use Rmunate\SqlServerLite\Traits\CommonFunctions;
use Rmunate\SqlServerLite\Traits\AvailableDrivers;

class SQLServer extends BaseSQLServer
{
    use Attributes,AvailableDrivers,CommonFunctions,Transaction,Methods,Constraints;

    private $PDO;
    private $credentials;
    private $response = [];

    /**
     * Constructor de la Clase de SQLServer
     * 
     * @param mixed $database
     * @param mixed $env
     * @param mixed $connection
     */
    public function __construct($database, $envPrefix, $connection)
    {
        if (!empty($database)) {
            $this->credentials = SetCredentials::fromArrayCredentials($database)->getCredentials();
        } else if (!empty($envPrefix)){
            $this->credentials = SetCredentials::fromEnvironment($envPrefix)->getCredentials();
        } else if (!empty($connection)){
            $this->credentials = SetCredentials::fromDatabaseConnections($connection)->getCredentials();
        }
    }

    /**
     * Establish the database connection.
     *
     * @return PDO Returns an instance of the PDO class representing the database connection.
     * @throws Exception Throws an exception if there is an error establishing the connection.
     */
    private function connectionPDO(): void
    {
        if ($this->hasValidPDOConnection()) return;

        try {
            $this->PDO = new PDO($this->credentials->dsn, $this->credentials->user, $this->credentials->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::SQLSRV_ATTR_QUERY_TIMEOUT => 0,
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
            ]);
        } catch (PDOException $e) {
            $error = is_array($e->errorInfo) ? strtoupper(implode(' - ', $e->errorInfo)) : $e->errorInfo . " - Ensure that the ODBC Driver and SQLServer are installed correctly.";
            throw new Exception($error, 0, $e);
        }
    }

    /**
     * Return the connection status.
     * 
     * @return object
     */
    final public function status(): object
    {
        try {
            $this->connectionPDO();
            return (object) [
                'status' => true,
                'message' => 'Connection Successful',
                'query' => $this
            ];
        } catch (Throwable $th) {
            return (object) [
                'status' => false,
                'message' => $th->getMessage(),
                'query' => null
            ];
        }
    }
    
    /**
     * Executes a SELECT query and returns the result set.
     *
     * @param string $statement The SELECT query statement.
     * @param array $params The optional parameters for prepared statements.
     * @return array The result set as an array of associative arrays.
     * @throws \Exception If the query is not a SELECT query or if there is an error executing the query.
     */
    final public function select(string $statement, array $params = []): static
    {
        // Check if the query is a SELECT query
        if ($this->isSelectQuery($statement)) {
            throw new \Exception(Messages::notIsSelectQueryException());
        }

        try {
            $this->connectionPDO();

            // Prepare the statement
            $stmt = $this->PDO->prepare($statement);

            // Bind the parameters if provided
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $value);
                }
            }

            // Execute the query
            $stmt->execute();

            // Fetch the result set as an associative array
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->response = $rows;

            return $this;
        } catch (\Exception $e) {
            throw new \Exception(Messages::selectException('Error executing SQL query: ' . $e->getMessage()));
        }
    }

    /**
     * Execute an UPDATE query.
     *
     * @param string $statement The UPDATE query statement.
     * @param array $params The array of parameters for the prepared statement.
     * @return bool Returns true if the query is executed successfully, false otherwise.
     * @throws Exception If there is an error executing the SQL query.
     */
    final public function update(string $statement, array $params = []): bool
    {
        // Check if the query is an UPDATE query
        if (!$this->isUpdateQuery($statement)) {
            throw new \Exception(Messages::notIsUpdateQueryException());
        }

        try {
            // Create Connection
            $this->connectionPDO();

            // Prepare the statement
            $stmt = $this->PDO->prepare($statement);

            // Bind the parameters if provided
            if (!empty($params)) {
                foreach ($params as $key => &$value) {
                    $stmt->bindParam($key, $value);
                }
            }

            // Execute the query
            $response = $stmt->execute();

            return $response && $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception(Messages::updateException('Error executing SQL query: ' . $e->getMessage()));
        }
    }

    /**
     * Execute an INSERT query.
     *
     * @param string $statement The INSERT query statement.
     * @param array $params The array of parameters for the prepared statement.
     * @return bool Returns true if the INSERT query was successful, false otherwise.
     * @throws \Exception If there is an error executing the SQL query.
     */
    final public function insert(string $statement, array $params = []): bool
    {
        // Check if the query is not an INSERT query
        if (!$this->isInsertQuery($statement)) {
            throw new \Exception(Messages::notIsInsertQueryException());
        }

        try {
            // Create Connection
            $this->connectionPDO();

            /* Validate Type of Query */
            if (!empty($params)) {
                // Prepare the statement with parameters
                $stmt = $this->PDO->prepare($statement);
                foreach ($params as $key => &$value) {
                    $stmt->bindParam($key, $value);
                }
                $response = $stmt->execute();
                return $response && $stmt->rowCount() > 0;
            } else {
                // Execute the query
                $response = $this->PDO->exec($statement);
                return $response !== false;
            }

        } catch (\Exception $e) {
            throw new \Exception(Messages::insertException('Error executing SQL query: ' . $e->getMessage()));
        }
    }

    /**
     * Execute a DELETE query.
     *
     * @param string $statement The DELETE query statement.
     * @param array $params The array of parameters for the prepared statement.
     * @return bool Returns true if the DELETE query was successful, false otherwise.
     * @throws \Exception If there is an error executing the SQL query.
     */
    final public function delete(string $statement, array $params = []): bool
    {
        // Check if the query is not a DELETE query
        if (!$this->isDeleteQuery($statement)) {
            throw new \Exception(Messages::notIsDeleteQueryException());
        }

        try {
            // Create Connection
            $this->connectionPDO();

            /* Validate Type of Query */
            if (!empty($params)) {
                // Prepare the statement with parameters
                $stmt = $this->PDO->prepare($statement);
                foreach ($params as $key => &$value) {
                    $stmt->bindParam($key, $value);
                }
                $stmt->execute($params);
                return $stmt->rowCount() > 0;
            } else {
                // Execute the query
                $stmt = $this->PDO->exec($statement);
                return $stmt !== false;
            }

        } catch (\Exception $e) {
            throw new \Exception(Messages::deleteException('Error executing SQL query: ' . $e->getMessage()));
        }
    }

    /**
     * Ejecuta un procedimiento almacenado y devuelve el resultado.
     *
     * @param string $procedure El nombre del procedimiento almacenado.
     * @param array $params Los parámetros para el procedimiento almacenado (opcional).
     * @return array El resultado del procedimiento almacenado como un arreglo asociativo.
     * @throws \Exception Si hay un error al ejecutar el procedimiento almacenado.
     */
    final public function executeProcedure(string $procedure): static
    {
        // Check if the query is not an DELETE query
        if ($this->isStoredProcedure($procedure)) {
            throw new \Exception(Messages::notIsProcedureException());
        }

        try {
            // Crear conexión
            $this->connectionPDO();
            
            // Preparar la sentencia
            $stmt = $this->PDO->prepare($statement);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado del procedimiento almacenado como un arreglo asociativo
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->response = $result;
            return $this;
            
        } catch (\Exception $e) {

            throw new \Exception(Messages::executeProcedureException('Error executing stored procedure: ' . $e->getMessage()));
        }
    }

    /**
     * Ejecuta un procedimiento almacenado para transacciones y devuelve un valor booleano.
     *
     * @param string $procedure El nombre del procedimiento almacenado.
     * @param array $params Los parámetros para el procedimiento almacenado (opcional).
     * @return bool True si el procedimiento almacenado se ejecutó correctamente, false en caso contrario.
     * @throws \Exception Si hay un error al ejecutar el procedimiento almacenado.
     */
    final public function executeTransactionalProcedure(string $procedure): bool
    {
        // Check if the query is not an DELETE query
        if ($this->isStoredProcedure($procedure)) {
            throw new \Exception(Messages::notIsProcedureException());
        }

        try {
            // Crear conexión
            $this->connectionPDO();

            // Preparar la sentencia
            $stmt = $this->PDO->prepare($procedure);
            
            // Ejecutar la consulta
            $result = $stmt->execute();
            
            $stmt->closeCursor();

            return $result;

        } catch (\Exception $e) {

            throw new \Exception(Messages::executeProcedureException('Error executing transactional stored procedure: ' . $e->getMessage()));
        }
    }

    /**
     * Return the first element of the response
     *
     * @param string $type The type of data to return ("object" or "array")
     * @return mixed|null The first element of the response or null if the response is empty
     */
    final public function first(string $type = "object"): mixed
    {
        $data = ($this->isNonEmptyArray()) ? reset($this->response) : null;
        return ($type === "object") ? $this->toObject($data) : $data;
    }

    /**
     * Return the last element of the response as an object
     *
     * @param string $type The type of data to return ("object" or "array")
     * @return mixed|null The last element of the response as an object or null if the response is empty or not an array
     */
    final public function last(string $type = "object"): mixed
    {
        $data = ($this->isNonEmptyArray()) ? end($this->response) : null;
        return ($type === "object") ? $this->toObject($data) : $data;
    }

    /**
     * Return instance collect
     *
     * @param string $type The type of data to return ("object" or "array")
     * @return mixed
     * @throws \Exception If called outside of Laravel
     */
    final public function collect(string $type = "object"): mixed
    {
        if (!$this->inLaravel()) {
            throw new \Exception(Messages::outsideOfLaravel());
        }

        if ($type === "object") {
            return ($this->isNonEmptyArray()) ? collect($this->toObject($this->response)) : collect([]);
        } else {
            return ($this->isNonEmptyArray()) ? collect($this->response) : collect([]);
        }
    }

    /**
     * Return final query
     *
     * @param string $type The type of data to return ("object" or "array")
     * @return mixed
     */
    final public function get(string $type = "object"): mixed
    {
        $data = ($this->isNonEmptyArray()) ? $this->response : [];
        return ($type === "object") ? $this->toObject($data) : $data;
    }

    /**
     * Return the count of elements in the response
     *
     * @return int The count of elements in the response
     */
    final public function count(): int
    {
        return count($this->response);
    }

    /**
     * Alias of count().
     *
     * @return int The number of elements in the array.
     */
    final public function sizeof()
    {
        return $this->count();
    }

    /**
     * Split an array into chunks.
     *
     * @param int $size The size of each chunk.
     * @param bool $preserve_keys Whether to preserve the keys in the chunks.
     * @return $this The current instance of the object
     */
    final public function chunk(int $size, bool $preserve_keys = false)
    {
        if ($this->isNonEmptyArray()) {
            $chunkedArray = array_chunk($this->response, $size, $preserve_keys);
            return $chunkedArray;
        }
        return null;
    }

    /**
     * Fill an array with values.
     *
     * @param int $start_index The first index to fill.
     * @param int $num The number of elements to fill.
     * @param mixed $value The value to fill.
     * @return $this The current instance of the object
     */
    final public function fill(int $start_index, int $num, $value)
    {
        $filledArray = array_fill($start_index, $num, $value);
        return $filledArray;
    }

    /**
     * Fill an array with values, specifying keys.
     *
     * @param array $keys The keys to use for filling.
     * @param mixed $value The value to fill.
     * @return $this The current instance of the object
     */
    final public function fillKeys(array $keys, $value)
    {
        $filledArray = array_fill_keys($keys, $value);
        return $filledArray;
    }

    /**---------------------------------------------------------------- */

    /**
     * Deprecated method
     * Executes a stored procedure and returns the result.
     *
     * @param string $statement The stored procedure statement.
     * @param bool $return Determines whether to return the result or not.
     * @return mixed The result of the stored procedure.
     * @deprecated Use appropriate methods for executing stored procedures.
     */
    public function procedure(string $statement, $return = true)
    {
        if ($return) {
            return $this->executeProcedure($statement);
        } else {
            return $this->executeTransactionalProcedure($statement);
        }
    }

    /**
     * Deprecated method
     * Return the first element of the response as an object
     *
     * @return mixed|null The first element of the response as an object or null if the response is empty or not an array
     * @deprecated Use the `first` method instead.
     */
    public function firstObject(): mixed
    {
        if ($this->isNonEmptyArray()) {
            $firstElement = reset($this->response);
            return $this->toObject($firstElement);
        }
        return null;
    }

    /**
     * Deprecated method
     * Return the last element of the response as an object
     *
     * @return mixed|null The last element of the response as an object or null if the response is empty or not an array
     * @deprecated Use the `last` method instead.
     */
    public function lastObject(): mixed
    {
        if ($this->isNonEmptyArray()) {
            $lastElement = end($this->response);
            return $this->toObject($lastElement);
        }
        return null;
    }

    /**
     * Deprecated method
     * Return final query
     *
     * @return mixed
     * @deprecated Use the `get` method instead.
     */
    public function getObjects(): mixed
    {
        return ($this->isNonEmptyArray()) ? $this->toObject($this->response) : null;
    }
}