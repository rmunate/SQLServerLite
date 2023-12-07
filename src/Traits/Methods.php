<?php

namespace Rmunate\SqlServerLite\Traits;

trait Methods
{
    public function orderBy(string $column, string $dir = 'ASC')
    {
        $this->oder = $column;
        $this->direction = $dir;

        return $this;
    }

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

    public function first()
    {
        $this->execGeneral();

        if (!empty($this->response)) {
            return reset($this->response);
        }

        return null;
    }

    public function last()
    {
        $this->execGeneral();

        if (!empty($this->response)) {
            return end($this->response);
        }

        return null;
    }

    public function count()
    {
        $this->execGeneral();

        if (!empty($this->response)) {
            return count($this->response);
        }

        return 0;
    }

    public function all()
    {
        return $this->get();
    }

    public function pluck($value, $index = null)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->pluck($value, $index);
    }

    public function value(string $value)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->value($value);
    }

    public function chunk(int $amount, Closure $callback)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->chunk($amount, $callback);
    }

    public function lazy()
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->lazy();
    }

    public function max(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->max($column);
    }

    public function min(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->min($column);
    }

    public function sum(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->sum($column);
    }

    public function avg(string $column)
    {
        $this->execGeneral();

        return (new SQLServerResponse($this->response))->avg($column);
    }
}
