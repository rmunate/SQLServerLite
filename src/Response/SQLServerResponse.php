<?php

namespace Rmunate\SqlServerLite\Response;

use Illuminate\Support\Collection;

class SQLServerResponse extends Collection
{
    function __construct(array $data) {
        parent::__construct($data);
    }
    
}
