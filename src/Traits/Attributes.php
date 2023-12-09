<?php

namespace Rmunate\SqlServerLite\Traits;

use PDO;

trait Attributes
{
    /**
     * Set the query timeout for the sql queries.
     * @param int $seconds
     * 
     * @return $this Returns the current instance of the object.
     */
    public function setTimeOut(int $seconds = 0)
    {
        $this->connection->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, $seconds);

        return $this;
    }

    /**
     * Set the error mode for the PDO connection.
     * @param string $mode
     * 
     * @return $this Returns the current instance of the object.
     */
    public function setErrorMode(string $mode = 'exception')
    {
        $validModes = ['SILENT', 'WARNING', 'EXCEPTION'];

        if (!in_array(mb_strtoupper($mode), $validModes)) {
            $mode = 'EXCEPTION';
        }

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, constant('PDO::ERRMODE_'.mb_strtoupper($mode)));

        return $this;
    }

    /**
     * Set the SQL Server attribute dynamically based on the provided case.
     * @param string $case The case to set ("binary", "utf8", "system", or "default").
     * 
     * @return $this Returns the current instance of the object.
     */
    public function setEncoding(string $case = 'utf8')
    {
        $validCases = ['BINARY', 'UTF8', 'SYSTEM', 'DEFAULT'];

        if (!in_array(mb_strtoupper($case), $validCases)) {
            $case = 'UTF8';
        }

        $this->connection->setAttribute(PDO::SQLSRV_ATTR_ENCODING, constant('PDO::SQLSRV_ENCODING_'.mb_strtoupper($case)));

        return $this;
    }

    /**
     * Enable direct query for the PDO connection.
     *
     * @return $this Returns the current instance of the object.
     */
    public function setDirectQuery()
    {
        $this->connection->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);

        return $this;
    }

    /**
     * Set the ANSI_NULLS option for the PDO connection.
     *
     * @param string $value The value to set for the ANSI_NULLS option ("ON" or "OFF").
     *
     * @return $this Returns the current instance of the object.
     */
    public function setAnsiNulls(string $value = 'ON')
    {
        $value = mb_strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ANSI_NULLS {$value}");
        }

        return $this;
    }

    /**
     * Set the ANSI_PADDING option for the PDO connection.
     *
     * @param string $value The value to set for the ANSI_PADDING option ("ON" or "OFF").
     *
     * @return $this Returns the current instance of the object.
     */
    public function setAnsiPadding(string $value = 'ON')
    {
        $value = mb_strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ANSI_PADDING {$value}");
        }

        return $this;
    }

    /**
     * Set the ANSI_WARNINGS option for the PDO connection.
     *
     * @param string $value The value to set for the ANSI_WARNINGS option ("ON" or "OFF").
     *
     * @return $this Returns the current instance of the object.
     */
    public function setAnsiWarnings(string $value = 'ON')
    {
        $value = mb_strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ANSI_WARNINGS {$value}");
        }

        return $this;
    }

    /**
     * Set the ARITHABORT option for the PDO connection.
     *
     * @param string $value The value to set for the ARITHABORT option ("ON" or "OFF").
     *
     * @return $this Returns the current instance of the object.
     */
    public function setArithAbort(string $value = 'ON')
    {
        $value = mb_strtoupper($value);
        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ARITHABORT {$value}");
        }

        return $this;
    }

    /**
     * Enable NOCOUNT mode for the PDO connection.
     *
     * @return $this Returns the current instance of the object.
     */
    public function noCount($status = 'ON')
    {
        $stmt = ($status === 'ON' || $status === true) ? 'SET NOCOUNT ON;' : 'SET NOCOUNT OFF;';

        $this->connection->exec($stmt);

        return $this;
    }
}
