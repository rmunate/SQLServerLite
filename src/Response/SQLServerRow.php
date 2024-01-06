<?php

namespace Rmunate\SqlServerLite\Response;

class SQLServerRow
{
    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
