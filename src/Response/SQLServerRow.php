<?php

namespace Rmunate\SqlServerLite\Response;

/**
 * Class SQLServerRow
 *
 * This class represents a single row of data from a SQL Server query.
 * It dynamically assigns properties based on the provided associative array.
 *
 * @package Rmunate\SqlServerLite\Response
 */

class SQLServerRow
{
    /**
     * SQLServerRow constructor.
     *
     * Initializes the SQLServerRow object with the given values.
     * Each key in the array is used as a property name and each value
     * is assigned to the corresponding property.
     *
     * @param array $values Associative array of column names and values.
     */
    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
