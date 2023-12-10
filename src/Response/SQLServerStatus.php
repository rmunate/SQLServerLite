<?php

namespace Rmunate\SqlServerLite\Response;

/**
 * Class SQLServerStatus
 *
 * @package Rmunate\SqlServerLite\Response
 */
class SQLServerStatus
{
    /**
     * @var bool The connection status.
     */
    protected $status;

    /**
     * @var string The connection message.
     */
    protected $message;

    /**
     * @var mixed The query information.
     */
    protected $query;

    /**
     * Constructor to set properties from the connection status.
     *
     * @param array $properties The connection status properties.
     */
    public function __construct(array $properties)
    {
        $this->status = $properties['status'];
        $this->message = $properties['message'];
        $this->query = $properties['query'];
    }

    /**
     * Get the query property.
     *
     * @return mixed The query information.
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Get the isConnected property.
     *
     * @return bool The connection status.
     */
    public function isConnected()
    {
        return $this->status;
    }

    /**
     * Get the connection message.
     *
     * @return string The connection message.
     */
    public function getMessage()
    {
        return $this->message;
    }
}
