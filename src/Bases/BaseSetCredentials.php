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

namespace Rmunate\SqlServerLite\Bases;

/**
 * Abstract class representing a MSSQL database connection.
 */
abstract class BaseSetCredentials
{
    /**
     * The DSN (Data Source Name) for the database connection.
     *
     * @var string
     */
    private $dsn;

    /**
     * The username for the database connection.
     *
     * @var string
     */
    private $user;

    /**
     * The password for the database connection.
     *
     * @var string
     */
    private $password;

    /**
     * Create a new instance of the class from array credentials.
     *
     * @param array $credentials The credentials used for establishing the connection.
     * @return static Returns a new instance of the class with the connection established.
     */
    public static function fromArrayCredentials(array $credentials): static
    {
        $class = new static();
        $class->setCredentialsFromArray($credentials);
        return $class->establishConnection();
    }

    /**
     * Create a new instance of the class from environment variables.
     *
     * @param string $prefix The prefix used for the environment variables.
     * @return static Returns a new instance of the class with the connection established.
     */
    public static function fromEnvironment(string $prefix): static
    {
        $class = new static();
        $class->setCredentialsFromEnvironment($prefix);
        return $class->establishConnection();
    }

    /**
     * Create a new instance of the class from database connections.
     *
     * @param string $prefix The prefix used for the database connections.
     * @return static Returns a new instance of the class with the connection established.
     */
    public static function fromDatabaseConnections(string $prefix): static
    {
        $class = new static();
        $class->setCredentialsFromConnections($prefix);
        return $class->establishConnection();
    }

    /**
     * Set the database credentials from an array.
     *
     * @param array $database The array containing the database credentials.
     * @return static Returns the current instance of the class.
     * @throws InvalidArgumentException Throws an exception if required keys are missing or empty.
     */
    abstract public function setCredentialsFromArray(array $database): static;

    /**
     * Set the database credentials from environment variables.
     *
     * @param string $prefix The prefix used for the environment variables.
     * @return static Returns the current instance of the class.
     * @throws InvalidArgumentException Throws an exception if required environment variables are missing or poorly defined.
     */
    abstract public function setCredentialsFromEnvironment(string $prefix): static;

    /**
     * Set the database credentials from database connections.
     *
     * @param string $connectName The name of the database connection.
     * @return static Returns the current instance of the class.
     * @throws InvalidArgumentException Throws an exception if required keys are missing or empty.
     */
    abstract public function setCredentialsFromConnections(string $connectName): static;
}
