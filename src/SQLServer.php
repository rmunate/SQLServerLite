<?php

declare(strict_types=1);

namespace Rmunate\SqlServerLite;

use Rmunate\SqlServerLite\Bases\BaseSQLServer;
use Rmunate\SqlServerLite\Singleton\SQLServerSingleton;
use Rmunate\SqlServerLite\Traits\Attributes;
use Rmunate\SqlServerLite\Traits\Execute;
use Rmunate\SqlServerLite\Traits\Methods;
use Rmunate\SqlServerLite\Traits\Transactions;
use Rmunate\SqlServerLite\Utilities\Utilities;
use Rmunate\SqlServerLite\Validator\StatementsValidator;

class SQLServer extends BaseSQLServer
{
    use Attributes;
    use Execute;
    use Methods;
    use Transactions;

    private $connection;
    private $response;
    private $statement;
    private $operation;
    private $params;
    private $order;
    private $direction;
    private $constraints = false;
    private $constraintsTables;

    /**
     * Constructor to establish the initial connection.
     *
     * @param mixed  $credentials
     * @param string $connection
     * @param int    $loginTimeout
     */
    public function __construct($credentials, string $connection, int $loginTimeout = 0)
    {
        $this->connection = SQLServerSingleton::mount($credentials, $connection, $loginTimeout);
    }

    /**
     * Prepare the SELECT query and return it.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return static
     */
    public function select(string $statement, array $params = [])
    {
        $this->statement = Utilities::statementSanitize($statement);

        StatementsValidator::isSelect($this->statement);

        $this->operation = 'select';
        $this->params = $params;

        return $this;
    }

    /**
     * Prepare the Update query and return the result set.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return object
     */
    public function update(string $statement, array $params = [])
    {
        $this->statement = Utilities::statementSanitize($statement);

        StatementsValidator::isUpdate($this->statement);

        $this->operation = 'update';
        $this->params = $params;

        if (Utilities::unpreparedQuery($this->statement, $this->params)) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the Insert query and return the result set.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return object
     */
    public function insert(string $statement, array $params = [])
    {
        $this->statement = Utilities::statementSanitize($statement);

        StatementsValidator::isInsert($this->statement);

        $this->operation = 'insert';
        $this->params = $params;

        if (Utilities::unpreparedQuery($this->statement, $this->params)) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the Insert with query and return the result set.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return object
     */
    public function insertGetId(string $statement, array $params = [])
    {
        $this->statement = Utilities::statementSanitize($statement);

        StatementsValidator::isInsert($this->statement);

        $this->operation = 'insert_get_id';
        $this->params = $params;

        if (Utilities::unpreparedQuery($this->statement, $this->params)) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the Delete query and return the result set.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return mixed
     */
    public function delete(string $statement, array $params = [])
    {
        $this->statement = Utilities::statementSanitize($statement);

        StatementsValidator::isDelete($this->statement);

        $this->operation = 'delete';
        $this->params = $params;

        if (Utilities::unpreparedQuery($this->statement, $this->params)) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the store procedure query and return the result set.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return object
     */
    public function executeProcedure(string $statement, array $params = [])
    {
        $this->statement = Utilities::statementSanitize($statement);

        StatementsValidator::isProcedure($this->statement);

        $this->operation = 'execute_procedure';
        $this->params = $params;

        return $this;
    }

    /**
     * Prepare the transaction store procedure query and return the result set.
     *
     * @param string $statement
     * @param array  $params
     *
     * @return mixed
     */
    public function executeTransactionalProcedure(string $statement, array $params = [])
    {
        $this->statement = Utilities::statementSanitize($statement);

        StatementsValidator::isProcedure($statement);

        $this->operation = 'execute_transactional_procedure';
        $this->params = $params;

        if (Utilities::unpreparedQuery($this->statement, $this->params)) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Receive the params from the query.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function params(array $params)
    {
        $this->params = $params;

        if (Utilities::inArray($this->operation, ['update', 'insert', 'insert_get_id', 'delete', 'execute_transactional_procedure'])) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Set the property to disable constraints.
     *
     * @param mixed ...$tables Tables for which constraints should be disabled.
     *
     * @return $this Returns the current instance of the object.
     */
    public function noCheckConstraint(...$tables)
    {
        $this->constraints = true;
        $this->constraintsTables = $tables;

        return $this;
    }
}
