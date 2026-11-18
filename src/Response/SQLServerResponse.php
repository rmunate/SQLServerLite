<?php

namespace Rmunate\SqlServerLite\Response;

use Illuminate\Support\Collection;

/**
 * Class SQLServerResponse.
 *
 * This class represents a response from a SQL Server query.
 * It extends the Illuminate\Support\Collection class to leverage
 * Laravel's powerful collection functionalities.
 */
class SQLServerResponse extends Collection
{
    /**
     * @param \Illuminate\Contracts\Support\Arrayable|iterable|null $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
    }
}
