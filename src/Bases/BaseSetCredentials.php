<?php

namespace Rmunate\SqlServerLite\Bases;

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
     *
     * @return static Returns a new instance of the class with the connection established.
     */
    public static function fromArrayCredentials(array $credentials)
    {
        $class = new static();
        $class->setCredentialsFromArray($credentials);

        return $class->getCredentials();
    }

    /**
     * Create a new instance of the class from environment variables.
     *
     * @param string $prefix The prefix used for the environment variables.
     *
     * @return static Returns a new instance of the class with the connection established.
     */
    public static function fromEnvironment(string $prefix)
    {
        $class = new static();
        $class->setCredentialsFromEnvironment($prefix);

        return $class->getCredentials();
    }

    /**
     * Create a new instance of the class from database connections.
     *
     * @param string $prefix The prefix used for the database connections.
     *
     * @return static Returns a new instance of the class with the connection established.
     */
    public static function fromDatabaseConnections(string $prefix)
    {
        $class = new static();
        $class->setCredentialsFromConnections($prefix);

        return $class->getCredentials();
    }

    /**
     * Set the database credentials from an array.
     *
     * @param array $database The array containing the database credentials.
     *
     * @throws InvalidArgumentException Throws an exception if required keys are missing or empty.
     *
     * @return static Returns the current instance of the class.
     */
    abstract public function setCredentialsFromArray(array $database);

    /**
     * Set the database credentials from environment variables.
     *
     * @param string $prefix The prefix used for the environment variables.
     *
     * @throws InvalidArgumentException Throws an exception if required environment variables are missing or poorly defined.
     *
     * @return static Returns the current instance of the class.
     */
    abstract public function setCredentialsFromEnvironment(string $prefix);

    /**
     * Set the database credentials from database connections.
     *
     * @param string $connectName The name of the database connection.
     *
     * @throws InvalidArgumentException Throws an exception if required keys are missing or empty.
     *
     * @return static Returns the current instance of the class.
     */
    abstract public function setCredentialsFromConnections(string $connectName);
}
