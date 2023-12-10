<?php

namespace Rmunate\SqlServerLite\Traits;

use Closure;
use Rmunate\SqlServerLite\Response\SQLServerResponse;

trait Methods
{
    /**
     * Set the ordering properties.
     *
     * @param string $column Column to apply the order.
     * @param string $dir    [optional] The sorting option.
     *
     * @return $this The current instance of the object.
     */
    public function orderBy(string $column, string $dir = 'ASC')
    {
        $this->order = $column;
        $this->direction = $dir;

        return $this;
    }

    /**
     * Execute the query and apply ordering properties.
     *
     * @return object The current object.
     */
    public function get()
    {
        $this->execGeneral();

        if (!empty($this->order)) {
            if ($this->direction == 'ASC') {
                return (new SQLServerResponse($this->response))->sortBy($this->order)->values();
            } else {
                return (new SQLServerResponse($this->response))->sortByDesc($this->order)->values();
            }
        }

        return new SQLServerResponse($this->response);
    }

    /**
     * Apply the filter to return the first object.
     *
     * @return mixed The first object.
     */
    public function first()
    {
        $this->execGeneral();

        if (!empty($this->response)) {
            return reset($this->response);
        }

        return null;
    }

    /**
     * Apply the filter to return the last object.
     *
     * @return mixed The last object.
     */
    public function last()
    {
        $this->execGeneral();

        if (!empty($this->response)) {
            return end($this->response);
        }

        return null;
    }

    /**
     * Perform the count.
     *
     * @return int The count of objects.
     */
    public function count()
    {
        $this->execGeneral();

        return !empty($this->response) ? count($this->response) : 0;
    }

    /**
     * Execute the query.
     *
     * @return SQLServerResponse The current object.
     */
    public function all()
    {
        return $this->get();
    }

    /**
     * Perform the pluck for a column and index in the collection.
     *
     * @param mixed $value Column to perform the pluck.
     * @param null  $index [optional] Index for the pluck.
     *
     * @return mixed The result of the pluck operation.
     */
    public function pluck($value, $index = null)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->pluck($value, $index);
    }

    /**
     * Extract the selected value.
     *
     * @param string $value Column to select.
     *
     * @return SQLServerResponse The result of the value extraction.
     */
    public function value(string $value)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->value($value);
    }

    /**
     * The chunk method may be used to process large numbers.
     *
     * @param int          $amount   Amount to chunk.
     * @param null|Closure $callback Function to perform on each chunk.
     *
     * @return mixed The result of the chunk operation.
     */
    public function chunk(int $amount, Closure $callback = null)
    {
        $this->execGeneral();

        /** @phpstan-ignore-next-line */
        return (new SQLServerResponse($this->response))->chunk($amount, $callback);
    }

    /**
     * The lazy method works similarly to the chunk.
     *
     * @return mixed The result of the lazy operation.
     */
    public function lazy()
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->lazy();
    }

    /**
     * Return the maximum value in a column.
     *
     * @param string $column Column to filter.
     *
     * @return SQLServerResponse The result with the maximum value.
     */
    public function max(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->max($column);
    }

    /**
     * Return the minimum value in a column.
     *
     * @param string $column Column to filter.
     *
     * @return SQLServerResponse The result with the minimum value.
     */
    public function min(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->min($column);
    }

    /**
     * Return the sum for a column.
     *
     * @param string $column Column to filter.
     *
     * @return SQLServerResponse The result with the sum value.
     */
    public function sum(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->sum($column);
    }

    /**
     * Return the average for a column.
     *
     * @param string $column Column to filter.
     *
     * @return mixed The result with the average value.
     */
    public function avg(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->avg($column);
    }
}
