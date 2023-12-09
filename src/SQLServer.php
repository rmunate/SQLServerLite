<?php

declare(strict_types=1);

namespace Rmunate\SqlServerLite;

use Rmunate\SqlServerLite\Bases\BaseSQLServer;
use Rmunate\SqlServerLite\Singleton\SQLServerSingleton;
use Rmunate\SqlServerLite\Traits\Attributes;
use Rmunate\SqlServerLite\Traits\Execute;
use Rmunate\SqlServerLite\Traits\Methods;
use Rmunate\SqlServerLite\Traits\Transactions;
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

    /**
     * Construct to do the initial connection
     * @param mixed $credentials
     * @param string $connection
     * @param int $loginTimeout
     */
    public function __construct($credentials, string $connection, int $loginTimeout = 0)
    {
        $this->connection = SQLServerSingleton::mount($credentials, $connection, $loginTimeout);
    }

    /**
     * Prepare the Select query and return it
     * @param string $statement
     * @param array $params
     * 
     * @return object
     */
    public function select(string $statement, array $params = [])
    {
        /** replace tabs and space from the query */
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isSelect($this->statement);

        $this->operation = 'select';
        $this->params = $params;

        return $this;
    }

    /**
     * Prepare the Update query and return the result set.
     * @param string $statement
     * @param array $params
     * 
     * @return object
     */
    public function update(string $statement, array $params = [])
    {
        /** replace tabs and space from the query */
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isUpdate($this->statement);

        $this->operation = 'update';
        $this->params = $params;

        if ((strpos($this->statement, ':') === false) || (strpos($this->statement, ':') !== false && !empty($this->params))) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the Insert query and return the result set.
     * @param string $statement
     * @param array $params
     * 
     * @return object
     */
    public function insert(string $statement, array $params = [])
    {
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isInsert($this->statement);

        $this->operation = 'insert';
        $this->params = $params;

        if ((strpos($this->statement, ':') === false) || (strpos($this->statement, ':') !== false && !empty($this->params))) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the Insert with query and return the result set.
     * @param string $statement
     * @param array $params
     * 
     * @return object
     */
    public function insertGetId(string $statement, array $params = [])
    {
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isInsert($this->statement);

        $this->operation = 'insert_get_id';
        $this->params = $params;

        if ((strpos($this->statement, ':') === false) || (strpos($this->statement, ':') !== false && !empty($this->params))) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the Delete query and return the result set.
     * @param string $statement
     * @param array $params
     * 
     * @return boolean
     */
    public function delete(string $statement, array $params = [])
    {
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isDelete($this->statement);

        $this->operation = 'delete';
        $this->params = $params;

        if ((strpos($this->statement, ':') === false) || (strpos($this->statement, ':') !== false && !empty($this->params))) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Prepare the store procedure query and return the result set.
     * @param string $statement
     * @param array $params
     * 
     * @return object
     */
    public function executeProcedure(string $statement, array $params = [])
    {
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isProcedure($this->statement);

        $this->operation = 'execute_procedure';
        $this->params = $params;

        return $this;
    }

    /**
     * Prepare the transaction store procedure query and return the result set.
     * @param string $statement
     * @param array $params
     * 
     * @return boolean
     */
    public function executeTransactionalProcedure(string $statement, array $params = [])
    {
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isProcedure($statement);

        $this->operation = 'execute_transactional_procedure';
        $this->params = $params;

        if ((strpos($this->statement, ':') === false) || (strpos($this->statement, ':') !== false && !empty($this->params))) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * receive the params from the query
     * @param array $params
     * 
     * @return array
     */
    public function params(array $params)
    {
        $this->params = $params;

        if (in_array($this->operation, ['update', 'insert', 'insert_get_id', 'delete', 'execute_transactional_procedure'])) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    /**
     * Set propierty constraints for set disable the constraint
     * @return boolean
     */
    public function noCheckConstraint()
    {
        $this->constraints = true;

        return $this;
    }
}
