<?php

namespace Rmunate\SqlServerLite\Response;

class SQLServerStatus
{
    protected $status;
    protected $message;
    protected $query;

    /**
     * set propierties from status connection
     * @param array $propierties
     */
    public function __construct(array $propierties)
    {
        $this->status = $propierties['status'];
        $this->message = $propierties['message'];
        $this->query = $propierties['query'];
    }

    /**
     * set the query propierty
     * @return $this query
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * set the isConnected propierty
     * @return $this isConnected
     */
    public function isConnected()
    {
        return $this->status;
    }
}
