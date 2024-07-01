<?php

namespace Rmunate\SqlServerLite\Validator;

use PDO;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;

class SQLServerValidator
{
    /**
     * @var array Array of database connection credentials.
     */
    private $credentials;

    /**
     * @var string Database connection name.
     */
    private $connection;

    /**
     * SQLServerValidator constructor.
     *
     * @param array  $credentials Database connection credentials.
     * @param string $connection  Database connection name.
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
            'host'     => 'The <host> Is Not Present In The Connection Config File',
            'database' => 'The <database> Is Not Present In The Connection Config File',
            'username' => 'The <username> Is Not Present In The Connection Config File',
            'password' => 'The <password> Is Not Present In The Connection Config File',
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
            throw SQLServerException::create("The 'sqlsrv' Driver Is Not Installed In The Php Environment.");
        }

        // Verify if the array config has data.
        if (empty($this->credentials)) {
            throw SQLServerException::create("The Connection '{$this->connection}' Is Not Configured In <config/catabase.php>, Within The Connections Array");
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
