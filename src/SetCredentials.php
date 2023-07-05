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

namespace Rmunate\SqlServerLite;

use Exception;
use InvalidArgumentException;
use Rmunate\SqlServerLite\Bases\BaseSetCredentials;
use Rmunate\SqlServerLite\Exceptions\Messages;
use Rmunate\SqlServerLite\Traits\CommonFunctions;

final class SetCredentials extends BaseSetCredentials
{
    use CommonFunctions;

    private string $dsn;
    private string $user;
    private string $password;

    /**
     * Set the database credentials from an array.
     *
     * @param array $credentials The array containing the database credentials.
     *
     * @throws InvalidArgumentException Throws an exception if required keys are missing or empty.
     *
     * @return static Returns the current instance of the class.
     */
    public function setCredentialsFromArray(array $credentials)
    {
        $requiredKeys = ['server', 'database', 'user', 'password'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $credentials) || empty(trim($credentials[$key]))) {
                throw new InvalidArgumentException(Messages::nonExistentKeyException($key));
            }
        }

        $this->dsn = $this->buildDsn($credentials);
        $this->user = trim($credentials['user']);
        $this->password = trim($credentials['password']);

        return $this;
    }

    /**
     * Set the database credentials from environment variables.
     *
     * @param string $prefix The prefix used for the environment variables.
     *
     * @throws InvalidArgumentException Throws an exception if required environment variables are missing or poorly defined.
     *
     * @return static Returns the current instance of the class.
     */
    public function setCredentialsFromEnvironment(string $prefix)
    {
        $database = [
            'server'   => env($prefix.'_SQLSRV_NAME', null),
            'instance' => env($prefix.'_SQLSRV_INSTANCE', null),
            'port'     => env($prefix.'_SQLSRV_PORT', null),
            'database' => env($prefix.'_SQLSRV_DATABASE', null),
            'user'     => env($prefix.'_SQLSRV_USER', null),
            'password' => env($prefix.'_SQLSRV_PASS', null),
        ];

        $requiredKeys = ['server', 'database', 'user', 'password'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $database) || empty(trim($database[$key]))) {
                throw new InvalidArgumentException(Messages::undefinedEnvironmentVariableException());
            }
        }

        $this->dsn = $this->buildDsn($database);
        $this->user = trim($database['user']);
        $this->password = trim($database['password']);

        return $this;
    }

    /**
     * Set the database credentials from database connections.
     *
     * @param string $connectName The name of the database connection.
     *
     * @throws InvalidArgumentException Throws an exception if required keys are missing or empty.
     * @throws Exception                Throws an exception if outside of Laravel.
     *
     * @return static Returns the current instance of the class.
     */
    public function setCredentialsFromConnections(string $connectName)
    {
        if (!$this->inLaravel()) {
            throw new Exception(Messages::outsideOfLaravel());
        }

        $config = config('database.connections.'.$connectName);

        $database = [
            'server'   => $config['host'] ?? $config['server'] ?? null,
            'instance' => $config['instance'] ?? null,
            'port'     => $config['port'] ?? null,
            'database' => $config['database'] ?? null,
            'user'     => $config['username'] ?? $config['user'] ?? null,
            'password' => $config['password'] ?? null,
        ];

        $requiredKeys = ['server', 'database', 'user', 'password'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $database) || empty(trim($database[$key]))) {
                throw new InvalidArgumentException(Messages::nonExistentKeyLaravelException($key));
            }
        }

        $this->dsn = $this->buildDsn($database);
        $this->user = trim($database['user']);
        $this->password = trim($database['password']);

        return $this;
    }

    /**
     * Build the DSN (Data Source Name) from the database credentials.
     *
     * @param array $credentials The database credentials.
     *
     * @return string The built DSN.
     */
    private function buildDsn(array $credentials): string
    {
        $dsn = 'sqlsrv:Server='.trim($credentials['server'])
            .(isset($credentials['port']) && !empty($credentials['port']) && is_numeric($credentials['port'])
                ? ','.intval($credentials['port'])
                : '')
            .(isset($credentials['instance']) && !empty($credentials['instance'])
                ? '\\'.$credentials['instance']
                : '')
            .';Database='.trim($credentials['database']);

        return $dsn;
    }

    /**
     * Get the database credentials.
     *
     * @return object Returns an object containing the DSN, user, and password.
     */
    public function getCredentials(): object
    {
        return (object) [
            'dsn'      => $this->dsn,
            'user'     => $this->user,
            'password' => $this->password,
        ];
    }
}
