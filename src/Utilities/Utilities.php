<?php

namespace Rmunate\SqlServerLite\Utilities;

class Utilities
{
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

    public static function getNameTable(string $statement)
    {
        $extractQuery = substr($statement, 6);

        return explode(" ", trim($extractQuery))[0];
    }
}
