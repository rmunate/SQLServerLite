<?php

namespace Rmunate\SqlServerLite\Traits;

use PDO;

trait AvailableDrivers
{
    /**
     * Get the available PDO drivers.
     *
     * @return array Returns an array of available PDO drivers.
     */
    public static function getAvailableDrivers()
    {
        return PDO::getAvailableDrivers();
    }
}
