<?php

/**
 * Lasso/Enumerable - A implementation of ruby's Enumerable module written in PHP.
 * Copyright (C) 2016  Lars Olsson <lasso@lassoweb.se>
 *
 * This file is part of Lasso/Enumerable.
 *
 * Lasso/Enumerable is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Lasso/Enumerable is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Lasso/Enumerable. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Lasso\Enumerable;

trait Enumerable
{
    protected abstract function __each();

    /**
     * Returns true if all elements in the enumerable returns true from the callback.
     * Otherwise returns false.
     *
     * @param callable $callback
     * @return boolean
     */
    public function all(callable $callback)
    {
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns true if any elements in the enumerable returns true from the callback.
     * Otherwise returns false.
     *
     * @param callable $callback
     * @return boolean
     */
    public function any(callable $callback)
    {
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === true) {
                return true;
            }
        }
        return false;
    }

    public function each(callable $callback)
    {
        foreach ($this->__each() as $elem) {
            $callback($elem);
        }
    }

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
        $numElems = 0;
        if ($callback) {
            foreach ($this->__each() as $elem) {
                if ($callback($elem)) {
                    $numElems++;
                }
            }
        }
        else {
            foreach ($this->__each() as $elem) {
                $numElems++;
            }
        }
        return $numElems;
    }

    /**
     * Returns the first element in the enumerable that returns true from the callback.
     * If no matching element can be found, the provided default is returned instead.
     *
     * @param callable $callback
     * @return mixed
     */
    public function find(callable $callback, $default = null)
    {
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === true) {
                return $elem;
            }
        }
        return $default;
    }

    /**
     * Returns all elements in the enumerable that returns true from the callback.
     *
     * @param callable $callback
     * @return EnumerableArray
     */
    public function findAll(callable $callback)
    {
        $elems = new EnumerableArray();
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === true) {
                $elems->append($elem);
            }
        }
        return $elems;
    }

    /**
     * Applies callback to all elements in the enumerable and returns an EnumerableArray
     * with new result. 
     *
     * @param callable $callback
     * @return EnumerableArray
     */
    public function map(callable $callback)
    {
        $elems = new EnumerableArray();
        foreach ($this->__each() as $elem) {
            $elems->append($callback($elem));
        }
        return $elems;
    }

    public function member($needle)
    {
        foreach ($this->__each() as $elem) {
            if ($needle === $elem) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns an EnumerableArray with two elements, one EnumerableArray with all elements
     * in the enumerable that returns true from the callback, and one EnumerableArray with
     * all elements in the enumerable that returns false from the callback.
     *
     * @param callable $callback
     * @return EnumerableArray
     */
    public function partition(callable $callback)
    {
        $matching = new EnumerableArray();
        $not_matching = new EnumerableArray();
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === true) {
                $matching->append($elem);
            }
            else {
                $not_matching->append($elem);
            }
        }
        return new EnumerableArray([$matching, $not_matching]);
    }

    public function reduce(callable $callback, $initialValue = null)
    {
        $memo = $initialValue;
        foreach ($this->__each() as $elem) {
            $memo = $callback($memo, $elem);
        }
        return $memo;
    }

    public function reject(callable $callback)
    {
        $elems = new EnumerableArray();
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === false) {
                $elems->append($elem);
            }
        }
        return $elems;
    }
}
