<?php

namespace Rmunate\SqlServerLite\Traits;

use Exception;
use Rmunate\SqlServerLite\Exceptions\Messages;

trait Methods
{
    /**
     * Return the reversed response array.
     *
     * @return $this The current instance of the object
     */
    public function reverse()
    {
        if ($this->isNonEmptyArray()) {
            $data = array_reverse($this->response);
            $this->response = $data;
        }

        return $this;
    }

    /**
     * Return Unique Data.
     *
     * @return $this The current instance of the object
     */
    public function unique()
    {
        if ($this->isNonEmptyArray()) {
            $data = array_values(array_reduce($this->response, function ($carry, $item) {
                if (!in_array($item, $carry, true)) {
                    $carry[] = $item;
                }

                return $carry;
            }, []));
            $this->response = $data;
        }

        return $this;
    }

    /**
     * Sorts the elements of the array in ascending order.
     *
     * @param int $sort_flags [optional] The sorting flags.
     *
     * @return $this The current instance of the object
     */
    public function sort(int $sort_flags = SORT_REGULAR)
    {
        // Checks if $this->response is not empty and is an array
        if ($this->isNonEmptyArray()) {
            $sortedArray = $this->response;
            sort($sortedArray, $sort_flags);
            $this->response = $sortedArray;
        }

        return $this;
    }

    /**
     * Get the flipped version of each array in the response.
     *
     * @return $this The current instance of the object
     */
    public function flip()
    {
        if ($this->isNonEmptyArray()) {
            if (is_array($this->response[0])) {
                throw new Exception(Messages::methodFlipException());
            }
            $flipArray = array_flip($this->response);
            $this->response = $flipArray;
        }

        return $this;
    }

    /**
     * Get a slice of elements from the response.
     *
     * @param int      $offset        The starting offset of the slice
     * @param int|null $length        The length of the slice
     * @param bool     $preserve_keys Whether to preserve the original keys in the slice
     *
     * @return $this The current instance of the object
     */
    public function slice(int $offset, ?int $length = null, bool $preserve_keys = false)
    {
        if ($this->isNonEmptyArray()) {
            $sliceArray = array_slice($this->response, $offset, $length, $preserve_keys);
            $this->response = $sliceArray;
        }

        return $this;
    }

    /**
     * Get a specific column from the response array.
     *
     * @param string $column The name of the column to retrieve
     *
     * @return $this The current instance of the object
     */
    public function column($column)
    {
        if ($this->isNonEmptyArray()) {
            $sliceColumn = array_column($this->response, $column);
            $this->response = $sliceColumn;
        }

        return $this;
    }

    /**
     * Merge multiple arrays recursively into the response array.
     *
     * @param array ...$arrays The arrays to merge.
     *
     * @return $this Returns the current instance of the object.
     */
    public function merge(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $mergedArray = $this->response;
            foreach ($arrays as $array) {
                $mergedArray = $this->recursiveMerge($mergedArray, $array);
            }
            $this->response = $mergedArray;
        }

        return $this;
    }

    /**
     * Recursively merge two arrays.
     *
     * @param array $array1 The first array to merge.
     * @param array $array2 The second array to merge.
     *
     * @return array The merged array.
     */
    private function recursiveMerge(array $array1, array $array2)
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = $this->recursiveMerge($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }

    /**
     * Get random elements from the response.
     *
     * @param int $num The number of random elements to retrieve
     *
     * @return $this The current instance of the object
     */
    public function rand($num)
    {
        if ($this->isNonEmptyArray()) {
            $num = ($num <= count($this->response)) ? $num : count($this->response);
            shuffle($this->response);
            $selectedItems = array_slice($this->response, 0, $num);
            $this->response = $selectedItems;
        }

        return $this;
    }

    /**
     * Changes the case of all keys in an array.
     *
     * @param int $case [optional] The case to which the keys will be changed.
     *
     * @return $this The current instance of the object
     */
    public function keyCase($case = CASE_LOWER)
    {
        if ($this->isNonEmptyArray()) {
            $this->response = array_change_key_case($this->response, $case);
        }

        return $this;
    }

    /**
     * Filters the elements of the array using a callback function.
     *
     * @param callable|null $callback The callback function to use for filtering.
     * @param int           $flag     [optional] The flag to control the behavior of the filter.
     *
     * @return $this The current instance of the object
     */
    public function filter(callable $callback = null, int $flag = 0)
    {
        if ($this->isNonEmptyArray()) {
            $this->response = array_filter($this->response, $callback, $flag);
        }

        return $this;
    }

    /**
     * Applies a callback function to each element of the array.
     *
     * @param callable $callback The callback function to apply.
     *
     * @return $this The current instance of the object
     */
    public function map(callable $callback): array
    {
        if ($this->isNonEmptyArray()) {
            $this->response = array_map($callback, $this->response);
        }

        return $this;
    }

    /**
     * Pad an array to a specified length with a value.
     *
     * @param int   $size  The new size of the array.
     * @param mixed $value The value to pad if the array is smaller.
     *
     * @return $this The current instance of the object
     */
    public function pad(int $size, $value)
    {
        $paddedArray = array_pad($this->response, $size, $value);
        $this->response = $paddedArray;

        return $this;
    }

    /**
     * Pop the element off the end of array.
     *
     * @return mixed The popped element, or null if the array is empty.
     */
    public function pop()
    {
        if ($this->isNonEmptyArray()) {
            if (is_array(end($this->response))) {
                array_pop($this->response);
            } else {
                $lastKey = array_key_last($this->response);
                unset($this->response[$lastKey]);
            }
        }

        return $this;
    }

    /**
     * Push one or more elements onto the end of array.
     *
     * @param mixed ...$values The values to push.
     *
     * @return $this The current instance of the object
     */
    public function push(...$values)
    {
        foreach ($values as $value) {
            array_push($this->response, $value);
        }

        return $this;
    }

    /**
     * Shift an element off the beginning of array.
     *
     * @return mixed The shifted element, or null if the array is empty.
     */
    public function shift()
    {
        if ($this->isNonEmptyArray()) {
            if (is_array($this->response[0]) && count($this->response[0]) > 0) {
                array_shift($this->response);
            } else {
                reset($this->response);
                unset($this->response[key($this->response)]);
            }
        }

        return $this;
    }

    /**
     * Prepend one or more elements to the beginning of array.
     *
     * @param mixed ...$values The values to prepend.
     *
     * @return $this The current instance of the object
     */
    public function unshift(...$values)
    {
        foreach ($values as $value) {
            array_unshift($this->response, $value);
        }

        return $this;
    }

    /**
     * Return all the values of an array.
     *
     * @return $this The current instance of the object
     */
    public function values()
    {
        if ($this->isNonEmptyArray()) {
            $valuesArray = array_values($this->response);
            $this->response = $valuesArray;
        }

        return $this;
    }
}
