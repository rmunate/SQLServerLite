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

    public function __construct($credentials, string $connection, int $loginTimeout = 0)
    {
        $this->connection = SQLServerSingleton::mount($credentials, $connection, $loginTimeout);
    }

    public function select(string $statement, array $params = [])
    {
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isSelect($this->statement);

        $this->operation = 'select';
        $this->params = $params;

        return $this;
    }

    public function update(string $statement, array $params = [])
    {
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

    public function executeProcedure(string $statement, array $params = [])
    {
        $this->statement = trim(str_replace(["\r", "\n", "\t"], '', $statement));

        StatementsValidator::isProcedure($this->statement);

        $this->operation = 'execute_procedure';
        $this->params = $params;

        return $this;
    }

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

    public function params(array $params)
    {
        $this->params = $params;

        if (in_array($this->operation, ['update', 'insert', 'insert_get_id', 'delete', 'execute_transactional_procedure'])) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    public function noCheckConstraint()
    {
        $this->constraints = true;

        return $this;
    }
}
