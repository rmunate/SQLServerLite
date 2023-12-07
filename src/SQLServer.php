<?php

declare (strict_types = 1);

namespace Rmunate\SqlServerLite;

use PDO;
use Closure;
use Rmunate\SqlServerLite\Traits\Execute;
use Rmunate\SqlServerLite\Traits\Attributes;
use Rmunate\SqlServerLite\Bases\BaseSQLServer;
use Rmunate\SqlServerLite\Traits\Transactions;
use Rmunate\SqlServerLite\Response\SQLServerResponse;
use Rmunate\SqlServerLite\Singleton\SQLServerSingleton;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Validator\StatementsValidator;

class SQLServer extends BaseSQLServer
{
    use Attributes;
    use Execute;
    use Transactions;

    private $connection;
    private $response;
    private $statement;
    private $operation;
    private $params;
    private $order;
    private $direction;

    
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

        if ((strpos($this->statement, ":") === false) || (strpos($this->statement, ":") !== false && !empty($this->params))) {
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

        if ((strpos($this->statement, ":") === false) || (strpos($this->statement, ":") !== false && !empty($this->params))) {
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

        if ((strpos($this->statement, ":") === false) || (strpos($this->statement, ":") !== false && !empty($this->params))) {
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

        if ((strpos($this->statement, ":") === false) || (strpos($this->statement, ":") !== false && !empty($this->params))) {
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

        if ((strpos($this->statement, ":") === false) || (strpos($this->statement, ":") !== false && !empty($this->params))) {
            $this->execGeneral();

            return $this->response;
        }
        
        return $this;
    }

    public function params(array $params)
    {
        $this->params = $params;

        if (in_array($this->operation, ["update", "insert", "insert_get_id", "delete", "execute_transactional_procedure"])) {
            $this->execGeneral();

            return $this->response;
        }

        return $this;
    }

    public function orderBy(string $column, string $dir = "ASC")
    {
        $this->oder = $column;
        $this->direction = $dir;
        
        return $this;        
    }

    public function get()
    {
        $this->execGeneral();

        if (!empty($this->order)) {
            if ($dir == 'ASC') {
                return (new SQLServerResponse($this->response))->sortBy($this->column)->values();
            } else {
                return (new SQLServerResponse($this->response))->sortByDesc($this->column)->values();
            }
        }

        return new SQLServerResponse($this->response);
    }

    public function first()
    {
        $this->execGeneral();

        if (!empty($this->response)) {
            return reset($this->response);
        }

        return null;
    }

    public function last()
    {
        $this->execGeneral();
        
        if (!empty($this->response)) {
            return end($this->response);
        }

        return null;
    }

    public function count()
    {
        $this->execGeneral();
        
        if (!empty($this->response)) {
            return count($this->response);
        }

        return 0;
    }

    public function all()
    {
        return $this->get();
    }

    public function pluck($value, $index = null)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->pluck($value, $index);
    }

    public function value(string $value)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->value($value);
    }

    public function chunk(int $amount, Closure $callback)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->chunk($amount, $callback);
    }

    public function lazy()
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->lazy();
    }

    public function max(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->max($column);
    }

    public function min(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->min($column);
    }
    
    public function sum(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->sum($column);
    }

    public function avg(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->avg($column);
    }
}
