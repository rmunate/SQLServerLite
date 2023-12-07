<?php

namespace Rmunate\SqlServerLite\Traits;

use PDO;
use Rmunate\SqlServerLite\Utilities\Utilities;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\Validator\StatementsValidator;

trait Execute
{
    private function execGeneral(){

        match ($this->operation) {
            'select'                          => $this->execSelect(),
            'update'                          => $this->execUpdate(),
            'insert'                          => $this->execInsert(),
            'insert_get_id'                   => $this->execInsertGetId(),
            'delete'                          => $this->execDelete(),
            'execute_procedure'               => $this->execProcedure(),
            'execute_transactional_procedure' => $this->execTransactionalProcedure(),
        };
    }

    private function execSelect()
    {
        try {
            
            $PDO = $this->connection->prepare($this->statement);

            StatementsValidator::isValidParams($this->params);

            if (!empty($this->params)) {
                foreach ($this->params as $key => $value) {
                    if (strpos($this->statement, $key) !== false) {
                        $PDO->bindParam($key, $this->params[$key]);
                    }
                }
            }

            $PDO->execute();
            $rows = $PDO->fetchAll(PDO::FETCH_ASSOC);

            $this->response = $rows;

            return $this;

        } catch (\Exception $e) {

            throw SQLServerException::create('Error executing SQL Select Query: '.$e->getMessage());
        }
    }
    
    private function execUpdate()
    {

        try {
            
            $PDO = $this->connection->prepare($this->statement);

            StatementsValidator::isValidParams($this->params);

            if (!empty($this->params)) {
                foreach ($this->params as $key => $value) {
                    if (strpos($this->statement, $key) !== false) {
                        $PDO->bindParam($key, $this->params[$key]);
                    }
                }
            }

            $response = $PDO->execute();

            $this->response = $response && $PDO->rowCount() > 0;

        } catch (\Exception $e) {

            throw SQLServerException::create('Error executing SQL Update Query: '.$e->getMessage());

        }
    }

    private function execInsert()
    {
        try {

            if (!empty($this->params)) {
                
                $PDO = $this->connection->prepare($this->statement);

                if (Utilities::hasSubArrays($this->params)) {
                    
                    foreach ($this->params as $key => $param) {

                        foreach ($param as $key => $value) {
                            if (strpos($this->statement, $key) !== false) {
                                $PDO->bindParam($key, $param[$key]);
                            }
                        }
                
                        $response = $PDO->execute();
                    }

                } else {

                    foreach ($this->params as $key => $value) {
                        if (strpos($this->statement, $key) !== false) {
                            $PDO->bindParam($key, $this->params[$key]);
                        }
                    }
    
                    $response = $PDO->execute();
    
                }

                $this->response = $response && $PDO->rowCount() > 0;
                
            } else {
                
                $response = $this->connection->exec($this->statement);
                
                $this->response = $response > 0;
            }
        } catch (\Exception $e) {

            throw SQLServerException::create('Error executing SQL Insert Query: '.$e->getMessage());

        }
    }

    private function execInsertGetId()
    {
        try {

            if (!empty($this->params)) {
                
                $PDO = $this->connection->prepare($this->statement);

                if (Utilities::hasSubArrays($this->params)) {
                    
                    $ids = [];

                    foreach ($this->params as $key => $param) {

                        foreach ($param as $key => $value) {
                            if (strpos($this->statement, $key) !== false) {
                                $PDO->bindParam($key, $param[$key]);
                            }
                        }
                
                        $response = $PDO->execute();

                        array_push($ids, (($response && $PDO->rowCount() > 0) ? $this->connection->lastInsertId() : null));
                    }

                    $this->response = $ids;

                } else {

                    foreach ($this->params as $key => $value) {
                        if (strpos($this->statement, $key) !== false) {
                            $PDO->bindParam($key, $this->params[$key]);
                        }
                    }
    
                    $response = $PDO->execute();
    
                    $this->response = ($response && $PDO->rowCount() > 0) ? $this->connection->lastInsertId() : null;
                }

                
            } else {
                
                $response = $this->connection->exec($this->statement);
                
                $this->response = $response > 0;
            }

        } catch (\Exception $e) {

            throw SQLServerException::create('Error executing SQL Insert Query: '.$e->getMessage());

        }
    }

    private function execDelete()
    {

        try {

            if (!empty($this->params)) {

                $PDO = $this->connection->prepare($this->statement);

                foreach ($this->params as $key => $value) {
                    if (strpos($this->statement, $key) !== false) {
                        $PDO->bindParam($key, $this->params[$key]);
                    }
                }

                $PDO->execute();

                $this->response = $PDO->rowCount() > 0;

            } else {
                
                $PDO = $this->connection->exec($this->statement);

                $this->response = $PDO !== false;
            }
            
        } catch (\Exception $e) {

            throw SQLServerException::create('Error executing SQL Delete Query: '.$e->getMessage());
        }
    }

    private function execProcedure()
    {
        try {
            
            $PDO = $this->connection->prepare($this->statement);

            StatementsValidator::isValidParams($this->params);

            if (!empty($this->params)) {
                foreach ($this->params as $key => $value) {
                    if (strpos($this->statement, $key) !== false) {
                        $PDO->bindParam($key, $this->params[$key]);
                    }
                }
            }

            $PDO->execute();
            $rows = $PDO->fetchAll(PDO::FETCH_ASSOC);

            $this->response = $rows;

            return $this;

        } catch (\Exception $e) {

            throw SQLServerException::create('Error executing SQL Store Procedure Query: '.$e->getMessage());
        }
    }

    private function execTransactionalProcedure()
    {
        try {

            $PDO = $this->connection->prepare($this->statement);

            StatementsValidator::isValidParams($this->params);

            if (!empty($this->params)) {
                foreach ($this->params as $key => $value) {
                    if (strpos($this->statement, $key) !== false) {
                        $PDO->bindParam($key, $this->params[$key]);
                    }
                }
            }

            $result = $PDO->execute();
            $PDO->closeCursor();

            $this->response = $result;

        } catch (\Exception $e) {

            throw SQLServerException::create('Error executing SQL Transactional Store Procedure Query: '.$e->getMessage());
            
        }
    }
}

