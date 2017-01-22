<?php

/**
 * lasso3000/enumerable - A implementation of ruby's Enumerable module written in PHP.
 * Copyright (C) 2016-2017  Lars Olsson <lasso@lassoweb.se>
 *
 * This file is part of lasso3000/enumerable.
 *
 * lasso3000/enumerable is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * lasso3000/enumerable is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with lasso3000/enumerable. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Lasso3000\Enumerable;

/**
 * An array-like object that implements the Enumerable trait. The EnumerableArray
 * does only support numeric keys.
 */
class EnumerableArray implements \ArrayAccess
{
    use Enumerable {
        count as enumerable_count;
    }

    /**
     * Internal storage for elements.
     *
     * @var array $elems
     */
    protected $elems;

    /**
     * Returns the number of elements in the enumerable. If a callback is provided,
     * only those alements that return true from the callback are counted.
     * Otherwise returns false.
     *
     * @param callable $callback
     * @return int
     */
    public function count(callable $callback = null)
    {
        if (null === $callback) {
            return count($this->elems);
        }
        return $this->enumerable_count($callback);
    }

    /**
     * Creates a new EnumerableArray from an existing array and returns it.
     *
     * @param array $elems
     * @return self
     */
    public static function fromArray(array $elems)
    {
        return new self($elems);
    }

    /**
     * Creates a new EnumerableArray from any Enumerable and returns it.
     *
     * @param mixed $enum
     * @return self
     */
    public static function fromEnumerable($enum)
    {
        return
            $enum->map(
                function($elem) {
                    return $elem;
                }
            );
    }

    /**
     * Creates a new EnumerableArray. If an array was provided as argument,
     * its elements will be added to the elements of the current object,
     * but all original keys will be dropped.
     *
     * @param array $elems
     */
    public function __construct(array $elems = [])
    {
        $this->elems = [];

        foreach ($elems as $elem) {
            $this->append($elem);
        }
    }

    /**
     * Adds an element to the end of the EnumerableArray.
     *
     * @param mixed $elem
     * @return null
     */
    public function append($elem)
    {
        $this->elems[] = $elem;
    }

    /**
     * Returns the elements of this object as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->elems;
    }

    /**
     * Returns whether the specified offset exists in this enumerable.
     *
     * @param int $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $filterOptions = [
            'options' => [
                'min_range' => 0,
                'max_range' => count($this->elems) - 1
            ]
        ];
        return filter_var($offset, FILTER_VALIDATE_INT, $filterOptions) !== false;
    }

    /**
     * Returns the element at the provided index.
     *
     * @param int $offset
     * @return mixed
     * @throws \OutOfRangeException
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfRangeException();
        }
        return $this->elems[$offset];
    }

    /**
     * Sets the element at the provided index to the provided value.
     *
     * @param int $offset
     * @param mixed $value
     * @return null
     * @throws \OutOfRangeException
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfRangeException();
        }
        $this->elems[$offset] = $value;
    }

    /**
     * Unsets the element at the provided index.
     *
     * @param int $offset
     * @return null
     * @throws \OutOfRangeException
     */
    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfRangeException();
        }
        unset($this->elems[$offset]);
    }

    /**
     * Returns one element at a time from the enumerable.
     */
    protected function __each()
    {
        foreach ($this->elems as $elem) {
            yield $elem;
        }
    }
}
