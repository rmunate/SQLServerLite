<?php

namespace Rmunate\SqlServerLite\Response;

use Illuminate\Support\Collection;

class SQLServerResponse extends Collection
{
    /**
     * Construct to do the initial connection
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
