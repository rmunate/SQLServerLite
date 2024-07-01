<?php

namespace Rmunate\SqlServerLite\Response;

use Illuminate\Support\Collection;

/**
 * Class SQLServerResponse
 *
 * This class represents a response from a SQL Server query.
 * It extends the Illuminate\Support\Collection class to leverage
 * Laravel's powerful collection functionalities.
 *
 * @package Rmunate\SqlServerLite\Response
 */

class SQLServerResponse extends Collection
{
    /**
     * SQLServerResponse constructor.
     *
     * Initializes the SQLServerResponse object with the given data.
     * This constructor calls the parent constructor to initialize
     * the collection with the provided array of data.
     *
     * @param array $data The data to initialize the collection.
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
