<?php

namespace Rmunate\SqlServerLite\Traits;

use PDO;

trait Attributes
{
    public function setTimeOut(int $seconds = 0)
    {
        $this->connection->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, $seconds);

        return $this;
    }

    public function setErrorMode(string $mode = 'exception')
    {
        $validModes = ['SILENT', 'WARNING', 'EXCEPTION'];

        if (!in_array(mb_strtoupper($mode), $validModes)) {
            $mode = 'EXCEPTION';
        }

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, constant('PDO::ERRMODE_' . mb_strtoupper($mode)));

        return $this;
    }

    public function setEncoding(string $case = 'utf8')
    {

        $validCases = ['BINARY', 'UTF8', 'SYSTEM', 'DEFAULT'];

        if (!in_array(mb_strtoupper($case), $validCases)) {
            $case = 'UTF8';
        }

        $this->connection->setAttribute(PDO::SQLSRV_ATTR_ENCODING, constant('PDO::SQLSRV_ENCODING_' . mb_strtoupper($case)));

        return $this;
    }

    public function setDirectQuery()
    {
        $this->connection->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);

        return $this;
    }

    public function setAnsiNulls(string $value = 'ON')
    {
        $value = mb_strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ANSI_NULLS {$value}");
        }

        return $this;
    }

    public function setAnsiPadding(string $value = 'ON')
    {
        $value = mb_strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ANSI_PADDING {$value}");
        }

        return $this;
    }

    public function setAnsiWarnings(string $value = 'ON')
    {
        $value = mb_strtoupper($value);
        
        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ANSI_WARNINGS {$value}");
        }

        return $this;
    }

    public function setArithAbort(string $value = 'ON')
    {
        $value = mb_strtoupper($value);
        if ($value === 'ON' || $value === 'OFF') {
            $this->connection->exec("SET ARITHABORT {$value}");
        }

        return $this;
    }

    public function noCount($status = "ON")
    {
        $stmt = ($status === "ON" ||  $status === true) ? "SET NOCOUNT ON;" : "SET NOCOUNT OFF;";
        
        $this->connection->exec($stmt);

        return $this;
    }
}
