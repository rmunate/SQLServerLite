<?php

namespace Rmunate\SqlServerLite\Traits;

trait Methods
{
    /**
     * set the order propierty by
     * @param string $column cloumn to apply the order
     * @param string $dir [optional] the sorting option
     * 
     * @return $this The current instance of the object
     */
    public function orderBy(string $column, string $dir = 'ASC')
    {
        $this->oder = $column;
        $this->direction = $dir;

        return $this;
    }

    /**
     * exec the query and apply orden propierty
     * @return $this The current object
     */
    public function get()
    {
        $this->execGeneral();

        if (!empty($this->order)) {
            if ($dir == 'ASC') {
                return (new SQLServerResponse($this->response))->sortBy($this->column)->values();
            } else {
                return (new SQLServerResponse($this->response))->sortByDesc($this->column)->values();
            }
        }

        return new SQLServerResponse($this->response);
    }

    /**
     * apply the filter for return the first object
     * @return $this The first object
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
     * apply the filter for return the last object
     * @return $this The last object
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
     * do the count
     * @return $this The count objects
     */
    public function count()
    {
        $this->execGeneral();

        if (!empty($this->response)) {
            return count($this->response);
        }

        return 0;
    }

    /**
     * exec the query
     * @return $this The current object
     */
    public function all()
    {
        return $this->get();
    }

    /**
     * do the pluck for column and index in the collect
     * @param mixed $value column to do the pluck
     * @param null $index [optional]
     * 
     * @return collect
     */
    public function pluck($value, $index = null)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->pluck($value, $index);
    }

    /**
     * extract the value selected
     * @param string $value columnt to select
     * 
     * @return collect
     */
    public function value(string $value)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->value($value);
    }

    /**
     * the chunk method may be used to process large numbers
     * @param int $amount amount to chunk
     * @param Closure $callback function to do in the each chunk
     * 
     * @return collect
     */
    public function chunk(int $amount, Closure $callback)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->chunk($amount, $callback);
    }

    /**
     * The lazy method works similarly to the chunk
     * @return collect
     */
    public function lazy()
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->lazy();
    }

    /**
     * return a max value in a column
     * @param string $column column to filter
     * 
     * @return collect with the max value
     */
    public function max(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->max($column);
    }

    /**
     * return a min value in a column
     * @param string $column column to filter
     * 
     * @return collect with the min value
     */
    public function min(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->min($column);
    }

    /**
     * return a sum for a column
     * @param string $column column to filter
     * 
     * @return collect column with sum value
     */
    public function sum(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->sum($column);
    }

    /**
     * return a average for a column
     * @param string $column column to filter
     * 
     * @return collect column with average value
     */
    public function avg(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->avg($column);
    }
}
