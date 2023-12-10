<?php

namespace Rmunate\SqlServerLite\Validator;

use PDO;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;

class SQLServerValidator
{
    /**
     * @var array $credentials Array of database connection credentials.
     */
    private $credentials;

    /**
     * @var string $connection Database connection name.
     */
    private $connection;

    /**
     * SQLServerValidator constructor.
     *
     * @param array  $credentials Database connection credentials.
     * @param string $connection Database connection name.
     */
    public function __construct(array $credentials, string $connection)
    {
        $this->credentials = $credentials;
        $this->connection = $connection;
    }

    /**
     * Get the required data for the connection.
     *
     * @return array List of required data.
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
     * Get error messages for the connection.
     *
     * @return array List of error messages.
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
     * Verify the credentials and required data.
     *
     * @return bool Returns true if the verification is successful.
     */
    public function verify()
    {
        // Verify if the 'sqlsrv' driver is installed in the PHP environment.
        if (!in_array('sqlsrv', PDO::getAvailableDrivers())) {
            throw SQLServerException::create("The 'sqlsrv' driver is not installed in the PHP environment.");
        }

        // Verify if the array config has data.
        if (empty($this->credentials)) {
            throw SQLServerException::create("The connection '{$this->connection}' is not configured in config/database.php, within the connections array");
        }

        $indexes = array_keys($this->credentials);

        $errors = [];

        // Iterate to verify all required data.
        foreach ($this->mandatory() as $value) {
            if (!in_array($value, $indexes) || empty($this->credentials[$value])) {
                array_push($errors, $this->messages()[$value]);
            }
        }

        // If there are errors, throw an exception.
        if (!empty($errors)) {
            throw SQLServerException::create(implode(', ', $errors));
        }

        return true;
    }
}
