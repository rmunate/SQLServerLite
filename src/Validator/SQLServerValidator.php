<?php

namespace Rmunate\SqlServerLite\Validator;

use PDO;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;

class SQLServerValidator
{
    private $credentials;

    public function __construct(array $credentials, string $connection) {
        $this->credentials = $credentials;
        $this->connection = $connection;
    }
    
    public function mandatory()
    {
        return [
            "host",
            "database",
            "username",
            "password"
        ];
    }
    
    public function messages()
    {
        return [
            "host" => "El host no se encuentra presente en los datos de configuracion de conexion",
            "database" => "La database no se encuentra presente en los datos de configuracion de conexion",
            "username" => "El username no se encuentra presente en los datos de configuracion de conexion",
            "password" => "El password no se encuentra presente en los datos de configuracion de conexion",
        ];
    }
    
    public function verify()
    {
        if (!in_array("sqlsrv", PDO::getAvailableDrivers())) {
            throw SQLServerException::create("No se encuentra instalado el driver 'sqlsrv' en el ambiente del PHP en uso."); 
        }

        if (empty($this->credentials)) {
            throw SQLServerException::create("La conexion '{$this->connection}' no esta configurada en config/database.php, dentro del arreglo de conecciones");            
        }

        $indexes = array_keys($this->credentials);

        $errors = [];
        foreach ($this->mandatory() as $key => $value) {
            if (!in_array($value, $indexes) || empty($this->credentials[$value])) {
                array_push($errors, $this->messages()[$value]);
            }
        }

        if (!empty($errors)) {
            throw SQLServerException::create(implode(", ", $errors));
        }
        
        return true;
    }
}
