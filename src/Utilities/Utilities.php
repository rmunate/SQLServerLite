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
}
