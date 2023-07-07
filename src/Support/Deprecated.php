<?php

namespace Rmunate\SqlServerLite\Support;

trait Deprecated
{
    // ------------------ Attributes Trait ------------------ //

    /**
     * Enable NOCOUNT mode for the PDO connection.
     *
     * Note: This method is deprecated and will be removed in a future major version.
     * Please use the setDirectQuery() method instead.
     *
     * @return $this Returns the current instance of the object.
     */
    public function noCount()
    {
        $this->setDirectQuery();

        return $this;
    }

    // ------------------ Constraints Trait ------------------ //

    /**
     * Deprecated method: Disable foreign key constraints for all tables or specified tables.
     * Use disableForeignKeys() method instead.
     *
     * @param array $tables The array of table names.
     *
     * @throws Exception If there is an error disabling foreign keys.
     *
     * @return $this The current instance of the object.
     *
     * @deprecated This method is deprecated and will be removed in the next major version.
     */
    public function noCheck(array $tables = [])
    {
        try {
            return $this->disableForeignKeys($tables);
        } catch (Exception $e) {
            throw new Exception(Messages::disableForeignKeysException('Error disabling foreign keys: '.$e->getMessage()));
        }
    }

    /**
     * Deprecated method: Enable foreign key constraints for all tables or specified tables.
     * Use enableForeignKeys() method instead.
     *
     * @param array $tables The array of table names.
     *
     * @throws Exception If there is an error enabling foreign keys.
     *
     * @return $this The current instance of the object.
     *
     * @deprecated This method is deprecated and will be removed in the next major version.
     */
    public function check(array $tables = [])
    {
        try {
            return $this->enableForeignKeys($tables);
        } catch (Exception $e) {
            throw new Exception(Messages::enableForeignKeysException('Error enabling foreign keys: '.$e->getMessage()));
        }
    }

    // ------------------ SQLServer Methods ------------------ //

    /**
     * Deprecated method
     * Return the first element of the response as an object.
     *
     * @return mixed|null The first element of the response as an object or null if the response is empty or not an array
     *
     * @deprecated Use the `first` method instead.
     */
    public function firstObject()
    {
        if ($this->isNonEmptyArray()) {
            $firstElement = reset($this->response);

            return $this->toObject($firstElement);
        }

        return null;
    }

    /**
     * Deprecated method
     * Return the last element of the response as an object.
     *
     * @return mixed|null The last element of the response as an object or null if the response is empty or not an array
     *
     * @deprecated Use the `last` method instead.
     */
    public function lastObject()
    {
        if ($this->isNonEmptyArray()) {
            $lastElement = end($this->response);

            return $this->toObject($lastElement);
        }

        return null;
    }

    /**
     * Deprecated method
     * Return final query.
     *
     * @return mixed
     *
     * @deprecated Use the `get` method instead.
     */
    public function getObjects()
    {
        return ($this->isNonEmptyArray()) ? $this->toObject($this->response) : null;
    }

    /**
     * Deprecated method
     * Return final query.
     *
     * @return mixed
     *
     * @deprecated Use the `get` method instead.
     */
    public function getObject()
    {
        return ($this->isNonEmptyArray()) ? $this->toObject($this->response) : null;
    }

    /**
     * Deprecated method
     * Executes a stored procedure and returns the result.
     *
     * @param string $statement The stored procedure statement.
     * @param bool   $return    Determines whether to return the result or not.
     *
     * @return mixed The result of the stored procedure.
     *
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
     * Rewturn Data Column
     *
     * @param string $statement The stored procedure statement.
     *
     * @return mixed The result of the stored procedure.
     *
     * @deprecated Use appropriate methods for executing stored procedures.
     */
    public function getColumn(string $column)
    {
        if ($this->isNonEmptyArray()) {
            return array_column($this->response, $column);
        }

        return [];
    }

}
