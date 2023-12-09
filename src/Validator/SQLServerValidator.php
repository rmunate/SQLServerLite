<?php

namespace Rmunate\SqlServerLite\Validator;

use PDO;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;

class SQLServerValidator
{
    private $credentials;

    /**
     * set propierties for validate array config
     * @param array $credentials
     * @param string $connection
     */
    public function __construct(array $credentials, string $connection)
    {
        $this->credentials = $credentials;
        $this->connection = $connection;
    }

    /**
     * return Required data for the connection
     * @return $array
     */
    public function mandatory()
    {
        return [
            'host',
            'database',
            'username',
            'password',
        ];
    }

    /**
     * return error message for the connection
     * @return $array
     */
    public function messages()
    {
        return [
            'host'     => 'The host is not present in the connection config file',
            'database' => 'The database is not present in the connection config file',
            'username' => 'The username is not present in the connection config file',
            'password' => 'The password is not present in the connection config file',
        ];
    }

    /**
     * method to verify the credentials and Required data
     * @throws Exception Throws an exception if the verify cant do it.
     * @return bool
     */
    public function verify()
    {
        /** verify if the array config has the driver */
        if (!in_array('sqlsrv', PDO::getAvailableDrivers())) {
            throw SQLServerException::create("The 'sqlsrv' driver is not installed in the PHP environment in use.");
        }

        /** verify if the array config has data */
        if (empty($this->credentials)) {
            throw SQLServerException::create("The connection '{$this->connection}' is not configured in config/database.php, within the connections array");
        }

        $indexes = array_keys($this->credentials);

        $errors = [];
        /** foreach to verify all required data */
        foreach ($this->mandatory() as $key => $value) {
            if (!in_array($value, $indexes) || empty($this->credentials[$value])) {
                array_push($errors, $this->messages()[$value]);
            }
        }

        if (!empty($errors)) {
            throw SQLServerException::create(implode(', ', $errors));
        }

        return true;
    }
}
