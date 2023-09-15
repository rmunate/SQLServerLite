<?php

declare (strict_types = 1);

namespace Rmunate\SqlServerLite;

use Exception;
use PDO;
use PDOException;
use Rmunate\SqlServerLite\Bases\BaseSQLServer;
use Rmunate\SqlServerLite\Exceptions\Messages;
use Rmunate\SqlServerLite\Support\Deprecated;
use Rmunate\SqlServerLite\Traits\Attributes;
use Rmunate\SqlServerLite\Traits\AvailableDrivers;
use Rmunate\SqlServerLite\Traits\CommonFunctions;
use Rmunate\SqlServerLite\Traits\Constraints;
use Rmunate\SqlServerLite\Traits\Methods;
use Rmunate\SqlServerLite\Traits\Transaction;
use Throwable;

class SQLServer extends BaseSQLServer
{
    use Attributes;
    use AvailableDrivers;
    use CommonFunctions;
    use Transaction;
    use Methods;
    use Constraints;
    use Deprecated;

    private $PDO;
    private $credentials;
    private $response = [];
    private $charset;

    /**
     * Constructor de la Clase de SQLServer.
     *
     * @param mixed $database
     * @param mixed $env
     * @param mixed $connection
     */
    public function __construct($database, $envPrefix, $connection)
    {
        if (!empty($database)) {
            $this->credentials = SetCredentials::fromArrayCredentials($database);
        } elseif (!empty($envPrefix)) {
            $this->credentials = SetCredentials::fromEnvironment($envPrefix);
        } elseif (!empty($connection)) {
            $this->credentials = SetCredentials::fromDatabaseConnections($connection);
        }
        $this->charset = $this->credentials->charset ?? 'utf8';
    }

    /**
     * Establish the database connection.
     *
     * @throws Exception Throws an exception if there is an error establishing the connection.
     *
     * @return PDO Returns an instance of the PDO class representing the database connection.
     */
    private function connectionPDO()
    {
        if ($this->hasValidPDOConnection()) {
            return;
        }

        try {
            //Crear Conexion PDO
            $this->PDO = new PDO($this->credentials->dsn, $this->credentials->user, $this->credentials->password, [
                PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
                PDO::SQLSRV_ATTR_QUERY_TIMEOUT => 0,
                PDO::SQLSRV_ATTR_ENCODING      => PDO::SQLSRV_ENCODING_UTF8,
            ]);

            //Asignar Codificacion Cargada O Por Defecto
            $this->setEncoding($this->charset);

            //Suprimir las credenciales
            unset($this->credentials);
        } catch (PDOException $e) {
            $error = is_array($e->errorInfo) ? strtoupper(implode(' - ', $e->errorInfo)) : $e->errorInfo.' - Ensure that the ODBC Driver and SQLServer are installed correctly.';

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
                'status'  => true,
                'message' => 'Connection Successful',
                'query'   => $this,
            ];
        } catch (Throwable $th) {
            return (object) [
                'status'  => false,
                'message' => $th->getMessage(),
                'query'   => null,
            ];
        }
    }

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
    final public function select(string $statement, array $params = [])
    {
        // Check if the query is a SELECT query
        if (!$this->isSelectQuery($statement)) {
            throw new \Exception(Messages::notIsSelectQueryException());
        }

        try {
            $this->connectionPDO();

            // Prepare the statement
            $stmt = $this->PDO->prepare($statement);

            // Bind the parameters if provided
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $params[$key]);
                }
            }

            // Execute the query
            $stmt->execute();

            // Fetch the result set as an associative array
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->response = $rows;

            return $this;
        } catch (\Exception $e) {
            throw new \Exception(Messages::selectException('Error executing SQL query: '.$e->getMessage()));
        }
    }

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
                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $params[$key]);
                }
            }

            // Execute the query
            $response = $stmt->execute();

            return $response && $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception(Messages::updateException('Error executing SQL query: '.$e->getMessage()));
        }
    }

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
                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $params[$key]);
                }
                $response = $stmt->execute();

                return $response && $stmt->rowCount() > 0;
            } else {
                // Execute the query
                $response = $this->PDO->exec($statement);

                return $response !== false;
            }
        } catch (\Exception $e) {
            throw new \Exception(Messages::insertException('Error executing SQL query: '.$e->getMessage()));
        }
    }

    /**
     * Execute an INSERT query.
     *
     * @param string $statement The INSERT query statement.
     * @param array  $params    The array of parameters for the prepared statement.
     *
     * @throws \Exception If there is an error executing the SQL query.
     *
     * @return mixed The last inserted ID.
     */
    final public function insertGetId(string $statement, array $params = [])
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

                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $params[$key]);
                }
                $response = $stmt->execute();

                //Retorna El Ultimo ID
                return ($response && $stmt->rowCount() > 0) ? $this->PDO->lastInsertId() : false;
            } else {
                // Execute the query
                $response = $this->PDO->exec($statement);

                if ($response !== false) {
                    return $this->PDO->lastInsertId();
                } else {
                    return false;
                }
            }
        } catch (\Exception $e) {
            throw new \Exception(Messages::insertException('Error executing SQL query: '.$e->getMessage()));
        }
    }

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
                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $params[$key]);
                }
                $stmt->execute($params);

                return $stmt->rowCount() > 0;
            } else {
                // Execute the query
                $stmt = $this->PDO->exec($statement);

                return $stmt !== false;
            }
        } catch (\Exception $e) {
            throw new \Exception(Messages::deleteException('Error executing SQL query: '.$e->getMessage()));
        }
    }

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
    final public function executeProcedure(string $procedure, array $params = [])
    {
        // Check if the query is not an DELETE query
        if (!$this->isStoredProcedure($procedure)) {
            throw new \Exception(Messages::notIsProcedureException());
        }

        try {
            // Crear conexión
            $this->connectionPDO();

            $procedure = !empty($this->noCount) ? $this->noCount.$procedure : $procedure;

            // Preparar la sentencia
            $stmt = $this->PDO->prepare($procedure);

            // Bind the parameters if provided
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $params[$key]);
                }
            }

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado del procedimiento almacenado como un arreglo asociativo
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->response = $result;

            return $this;
        } catch (\Exception $e) {
            throw new \Exception(Messages::executeProcedureException('Error executing stored procedure: '.$e->getMessage()));
        }
    }

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
    final public function executeTransactionalProcedure(string $procedure, array $params = []): bool
    {
        // Check if the query is not an DELETE query
        if (!$this->isStoredProcedure($procedure)) {
            throw new \Exception(Messages::notIsProcedureException());
        }

        try {
            // Crear conexión
            $this->connectionPDO();

            $procedure = !empty($this->noCount) ? $this->noCount.$procedure : $procedure;

            // Preparar la sentencia
            $stmt = $this->PDO->prepare($procedure);

            // Bind the parameters if provided
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindParam($key, $params[$key]);
                }
            }

            // Ejecutar la consulta
            $result = $stmt->execute();

            $stmt->closeCursor();

            return $result;
        } catch (\Exception $e) {
            throw new \Exception(Messages::executeProcedureException('Error executing transactional stored procedure: '.$e->getMessage()));
        }
    }

    /**
     * Return the first element of the response.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @return mixed|null The first element of the response or null if the response is empty
     */
    final public function first(string $type = 'array')
    {
        $data = ($this->isNonEmptyArray()) ? reset($this->response) : null;

        return ($type === 'object') ? $this->toObject($data) : $data;
    }

    /**
     * Return the last element of the response as an object.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @return mixed|null The last element of the response as an object or null if the response is empty or not an array
     */
    final public function last(string $type = 'array')
    {
        $data = ($this->isNonEmptyArray()) ? end($this->response) : null;

        return ($type === 'object') ? $this->toObject($data) : $data;
    }

    /**
     * Return instance collect.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @throws \Exception If called outside of Laravel
     *
     * @return mixed
     */
    final public function collect(string $type = 'array')
    {
        if (!$this->inLaravel()) {
            throw new \Exception(Messages::outsideOfLaravel());
        }

        if ($type === 'object') {
            return ($this->isNonEmptyArray()) ? collect($this->toObject($this->response)) : collect([]);
        }

        return ($this->isNonEmptyArray()) ? collect($this->response) : collect([]);
    }

    /**
     * Return final query.
     *
     * @param string $type The type of data to return ("object" or "array")
     *
     * @return mixed
     */
    final public function get(string $type = 'array')
    {
        $data = ($this->isNonEmptyArray()) ? $this->response : [];

        return ($type === 'object') ? $this->toObject($data) : $data;
    }

    /**
     * Return the count of elements in the response.
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
     * @param int  $size          The size of each chunk.
     * @param bool $preserve_keys Whether to preserve the keys in the chunks.
     *
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
     * Applies a callback function to the elements of the array, reducing them to a single value.
     *
     * @param callable $callback The callback function to apply.
     * @param mixed    $initial  [optional] The initial value for the reduction.
     *
     * @return $this The current instance of the object
     */
    public function reduce(callable $callback, $initial = null)
    {
        if ($this->isNonEmptyArray()) {
            return array_reduce($this->response, $callback, $initial);
        }
    }
}
