<?php

namespace Rmunate\SqlServerLite\Utilities;

class Utilities
{
    /**
     * return true if the params has sub arrays
     * @param mixed $array
     * 
     * @return bool
     */
    public static function hasSubArrays($array)
    {
        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $elemento) {
            if (is_array($elemento)) {
                return true;
            }
        }

        return false;
    }

    /**
     * return the table name for disable constraint
     * @param string $statement
     * 
     * @return string
     */
    public static function getNameTable(string $statement)
    {
        $extractQuery = substr($statement, 6);

        return explode(' ', trim($extractQuery))[0];
    }
}
