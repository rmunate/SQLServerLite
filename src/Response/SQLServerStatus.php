<?php

namespace Rmunate\SqlServerLite\Response;

class SQLServerStatus
{
    protected $status;
    protected $message;
    protected $query;

    public function __construct(array $propierties)
    {
        $this->status = $propierties['status'];
        $this->message = $propierties['message'];
        $this->query = $propierties['query'];
    }

    public function query()
    {
        return $this->query;
    }

    public function isConnected()
    {
        return $this->status;
    }
}
