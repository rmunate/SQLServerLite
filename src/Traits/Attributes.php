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

namespace Rmunate\SqlServerLite\Traits;

use PDO;

trait Attributes
{
    /**
     * Set the query timeout for the PDO connection.
     *
     * @param int $seconds The timeout value in seconds.
     *
     * @return $this Returns the current instance of the object.
     */
    public function setTimeOut(int $seconds = 0)
    {
        $this->PDO->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, $seconds);

        return $this;
    }

    /**
     * Set the error mode for the PDO connection.
     *
     * @param string $mode The error mode to set ("silent", "warning", or "exception").
     *
     * @return $this Returns the current instance of the object.
     */
    public function setErrorMode(string $mode)
    {
        $validModes = ['silent', 'warning', 'exception'];
        $mode = strtolower($mode);

        if (!in_array($mode, $validModes)) {
            $mode = 'exception';
        }

        $this->PDO->setAttribute(PDO::ATTR_ERRMODE, constant('PDO::ERRMODE_'.strtoupper($mode)));

        return $this;
    }

    /**
     * Set the SQL Server attribute dynamically based on the provided case.
     *
     * @param string $case The case to set ("binary", "utf8", "system", or "default").
     *
     * @return $this Returns the current instance of the object.
     */
    public function setEncoding(string $case)
    {
        $validCases = ['binary', 'utf8', 'system', 'default'];
        $case = strtolower($case);

        if (!in_array($case, $validCases)) {
            $case = 'utf8';
        }

        $this->PDO->setAttribute(PDO::SQLSRV_ATTR_ENCODING, constant('PDO::SQLSRV_ENCODING_'.strtoupper($case)));

        return $this;
    }

    /**
     * Enable NOCOUNT mode for the PDO connection.
     *
     * @return $this Returns the current instance of the object.
     */
    public function setDirectQuery()
    {
        $this->PDO->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);

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
        $value = strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->PDO->exec("SET ANSI_NULLS $value");
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
        $value = strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->PDO->exec("SET ANSI_PADDING $value");
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
        $value = strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->PDO->exec("SET ANSI_WARNINGS $value");
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
        $value = strtoupper($value);

        if ($value === 'ON' || $value === 'OFF') {
            $this->PDO->exec("SET ARITHABORT $value");
        }

        return $this;
    }

    /**
     * Enable NOCOUNT mode for the PDO connection.
     *
     * Note: This method is deprecated and will be removed in a future major version.
     * Please use setDirectQuery() method instead.
     *
     * @return $this Returns the current instance of the object.
     */
    public function noCount()
    {
        $this->setDirectQuery();

        return $this;
    }
}
