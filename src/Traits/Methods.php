<?php

/*
 * Copyright (c) [2023] [RAUL MAURICIO UÑATE CASTRO]
 *
 * This library is open source software licensed under the MIT license.
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this library and associated
 * documentation files (the "Software"), to deal in the library without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the library,
 * and to permit persons to whom the library is furnished to do so, subject to the following conditions:
 *
 * - Use the library for commercial or non-commercial purposes.
 * - Modify the library and adapt it to your own needs.
 * - Distribute copies of the library.
 * - Sublicense the library.
 *
 * When using or distributing this library, it is required to include the following attribution in all copies or
 * substantial portions of the library:
 *
 * "[RAUL MAURICIO UÑATE CASTRO], the copyright holder of this library, must
 * be acknowledged and mentioned in all copies or derivatives of the library."
 *
 * In addition, if modifications are made to the library, it is requested to include an additional note in the
 * documentation or in any other means of notifying the changes made, stating:
 *
 * "This library has been modified from the original library developed by [RAUL MAURICIO UÑATE CASTRO]."
 *
 * THE LIBRARY IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE LIBRARY OR THE USE OR OTHER DEALINGS IN THE LIBRARY.
 */

namespace Rmunate\SqlServerLite\Traits;

trait Methods
{
    /**
     * Return the reversed response array
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
     * Return Unique Data
     * 
     * @return $this The current instance of the object
     */
    public function unique()
    {
        if ($this->isNonEmptyArray()) {
            $data = array_unique($this->response);
            $this->response = $data;
        }
        return $this;
    }

    /**
     * Sorts the elements of the array in ascending order.
     *
     * @param int $sort_flags [optional] The sorting flags.
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
     * Get the flipped version of each array in the response
     * 
     * @return $this The current instance of the object
     */
    public function flip()
    {
        if ($this->isNonEmptyArray()) {
            $flipArray = array_map('array_flip', $this->response);
            $this->response = $flipArray;
        }
        return $this;
    }

    /**
     * Get a slice of elements from the response
     * 
     * @param int $offset The starting offset of the slice
     * @param int|null $length The length of the slice
     * @param bool $preserve_keys Whether to preserve the original keys in the slice
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
     * Get a specific column from the response array
     * 
     * @param string $column The name of the column to retrieve
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
     * Merge multiple arrays into one.
     *
     * @param array ...$arrays The arrays to merge.
     * @return $this The current instance of the object
     */
    public function merge(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToMerge = array_merge([$this->response], ...$arrays);
            $mergedArray = array_merge_recursive(...$arraysToMerge);
            $this->response = $mergedArray;
        }
        return $this;
    }

    /**
     * Get random elements from the response
     * 
     * @param int $num The number of random elements to retrieve
     * @return $this The current instance of the object
     */
    public function rand($num)
    {
        if ($this->isNonEmptyArray()) {
            $arrayRand = array_rand($this->response, $num);
            $this->response = $arrayRand;
        }
        return $this;
    }

    /**
     * Calculates the difference between multiple arrays.
     *
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function diff(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToDiff = array_merge([$this->response], ...$arrays);
            $this->response = array_diff(...$arraysToDiff);
        }
        return $this;
    }

    /**
     * Calculates the intersection between multiple arrays.
     *
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function intersect(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToIntersect = array_merge([$this->response], ...$arrays);
            $this->response = array_intersect(...$arraysToIntersect);
        }
        return $this;
    }

     /**
     * Changes the case of all keys in an array.
     *
     * @param int $case [optional] The case to which the keys will be changed.
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
     * @param int $flag [optional] The flag to control the behavior of the filter.
     * @return $this The current instance of the object
     */
    public function filter(callable $callback = null, int $flag = 0): array
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
     * Applies a callback function to the elements of the array, reducing them to a single value.
     *
     * @param callable $callback The callback function to apply.
     * @param mixed $initial [optional] The initial value for the reduction.
     * @return $this The current instance of the object
     */
    public function reduce(callable $callback, $initial = null)
    {
        if ($this->isNonEmptyArray()) {
            $this->response = array_reduce($this->response, $callback, $initial);
        }
        return $this;
    }

    /**
     * Computes the difference of arrays with additional index check.
     *
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function diffAssoc(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToDiff = array_merge([$this->response], ...$arrays);
            $this->response = array_diff_assoc(...$arraysToDiff);
        }
        return $this;
    }

    /**
     * Computes the difference of arrays using keys for comparison.
     *
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function diffKey(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToDiff = array_merge([$this->response], ...$arrays);
            $this->response = array_diff_key(...$arraysToDiff);
        }
        return $this;
    }

    /**
     * Computes the difference of arrays with additional index check, using a callback function.
     *
     * @param callable $key_compare_func The callback function to use for key comparison.
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function diffUassoc(callable $key_compare_func, array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToDiff = array_merge([$this->response], ...$arrays);
            $this->response = call_user_func_array('array_diff_uassoc', $arraysToDiff, $key_compare_func);
        }
        return $this;
    }

    /**
     * Computes the difference of arrays using keys for comparison, using a callback function.
     *
     * @param callable $key_compare_func The callback function to use for key comparison.
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function diffUkey(callable $key_compare_func, array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToDiff = array_merge([$this->response], ...$arrays);
            $this->response = call_user_func_array('array_diff_ukey', $arraysToDiff, $key_compare_func);
        }
        return $this;
    }

    /**
     * Computes the intersection of arrays with additional index check.
     *
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function intersectAssoc(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToIntersect = array_merge([$this->response], ...$arrays);
            $this->response = array_intersect_assoc(...$arraysToIntersect);
        }
        return $this;
    }

    /**
     * Computes the intersection of arrays using keys for comparison.
     *
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function intersectKey(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToIntersect = array_merge([$this->response], ...$arrays);
            $this->response = array_intersect_key(...$arraysToIntersect);
        }
        return $this;
    }

    /**
     * Computes the intersection of arrays with additional index check, using a callback function.
     *
     * @param callable $key_compare_func The callback function to use for key comparison.
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function intersectUassoc(callable $key_compare_func, array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToIntersect = array_merge([$this->response], ...$arrays);
            $this->response = call_user_func_array('array_intersect_uassoc', $arraysToIntersect, $key_compare_func);
        }
        return $this;
    }

    /**
     * Computes the intersection of arrays using keys for comparison, using a callback function.
     *
     * @param callable $key_compare_func The callback function to use for key comparison.
     * @param array ...$arrays The arrays to compare.
     * @return $this The current instance of the object
     */
    public function intersectUkey(callable $key_compare_func, array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToIntersect = array_merge([$this->response], ...$arrays);
            $this->response = call_user_func_array('array_intersect_ukey', $arraysToIntersect, $key_compare_func);
        }
        return $this;
    }

    /**
     * Merge one or more arrays recursively.
     *
     * @param array ...$arrays The arrays to merge.
     * @return $this The current instance of the object
     */
    public function mergeRecursive(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToMerge = array_merge([$this->response], ...$arrays);
            $mergedArray = array_merge_recursive(...$arraysToMerge);
            $this->response = $mergedArray;
        }
        return $this;
    }

    /**
     * Pad an array to a specified length with a value.
     *
     * @param int $size The new size of the array.
     * @param mixed $value The value to pad if the array is smaller.
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
            return array_pop($this->response);
        }
        return null;
    }

    /**
     * Push one or more elements onto the end of array.
     *
     * @param mixed ...$values The values to push.
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
     * Replace recursive values in an array.
     *
     * @param array ...$arrays The arrays from which to replace values.
     * @return $this The current instance of the object
     */
    public function replaceRecursive(array ...$arrays)
    {
        if ($this->isNonEmptyArray()) {
            $arraysToReplace = array_merge([$this->response], ...$arrays);
            $replacedArray = array_replace_recursive(...$arraysToReplace);
            $this->response = $replacedArray;
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
            return array_shift($this->response);
        }
        return null;
    }

    /**
     * Prepend one or more elements to the beginning of array.
     *
     * @param mixed ...$values The values to prepend.
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

    /**
     * Sort an array using a case-insensitive "natural order" algorithm.
     *
     * @return $this The current instance of the object
     */
    public function natcasesort()
    {
        if ($this->isNonEmptyArray()) {
            natcasesort($this->response);
        }
        return $this;
    }

    /**
     * Create an array containing a range of elements.
     *
     * @param mixed $start The first value of the sequence.
     * @param mixed $end The final value of the sequence.
     * @param number $step The step between each value in the sequence.
     * @return $this The current instance of the object
     */
    public function range($start, $end, $step = 1)
    {
        $rangeArray = range($start, $end, $step);
        $this->response = $rangeArray;
        return $this;
    }

    /**
     * Sort an array by keys using a user-defined comparison function.
     *
     * @param callable $callback The comparison function.
     * @return $this The current instance of the object
     */
    public function uksort(callable $callback)
    {
        if ($this->isNonEmptyArray()) {
            uksort($this->response, $callback);
        }
        return $this;
    }

    /**
     * Sort an array using a user-defined comparison function.
     *
     * @param callable $callback The comparison function.
     * @return $this The current instance of the object
     */
    public function usort(callable $callback)
    {
        if ($this->isNonEmptyArray()) {
            usort($this->response, $callback);
        }
        return $this;
    }
        

}