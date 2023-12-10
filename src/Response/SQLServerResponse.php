<?php

namespace Rmunate\SqlServerLite\Response;

use Illuminate\Support\Collection;

class SQLServerResponse extends Collection
{
    /**
     * Constructor to initialize the connection.
     *
     * @param array $data The data to initialize the collection.
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
