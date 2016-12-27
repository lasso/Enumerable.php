<?php

/**
 * lasso3000/enumerable - A implementation of ruby's Enumerable module written in PHP.
 * Copyright (C) 2016  Lars Olsson <lasso@lassoweb.se>
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
 * The Enumerable trait will allow any object to work as a collection. The using class must
 * implement the __each method, all other methods in this trait only depend on that method. 
 */
trait Enumerable
{
    /**
     * Returns one element at a time from the enumerable. When implementing this method,
     * you should only return values using the yield keyword. Do not use the return keyword
     * to return values from this method!
     */
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
            return $numElems;
        }
        foreach ($this->__each() as $elem) {
            $numElems++;
        }
        return $numElems;
    }

    /**
     * Drops a number of elemens from the enumerable and returns the remaining
     * elements as an EnumerableArray.
     *
     * @param int $numElems
     * @return EnumerableArray
     */
    public function drop($numElems)
    {
        if (!is_int($numElems) || $numElems < 0) {
            throw new \InvalidArgumentException("Argument must be a positive integer or 0.");
        }
        $elems = new EnumerableArray();
        $numDropped = 0;
        foreach ($this->__each() as $elem) {
            if ($numDropped++ < $numElems) {
                continue;
            }
            $elems->append($elem);
        }
        return $elems;
    }

    /**
     * Drops elements until the provided callback returns true. The remaining elements
     * are returned as an EnumerableArray.
     *
     * @param callable $callback
     * @return EnumerableArray
     */
    public function dropWhile(callable $callback)
    {
        $elems = new EnumerableArray();
        $stillDropping = true;
        foreach ($this->__each() as $elem) {
            if ($stillDropping) {
                if ($callback($elem) === true) {
                    continue;
                }
                $stillDropping = false;
            }
            $elems->append($elem);
        }
        return $elems;
    }

    /**
     * Applies a callback on each element in the enumerable without returning any value.
     *
     * @param callable $callback
     * @return null
     */
    public function each(callable $callback)
    {
        foreach ($this->__each() as $elem) {
            $callback($elem);
        }
    }

    /**
     * Returns the first element in the enumerable that returns true from the callback.
     * If no matching element can be found, the provided default is returned instead.
     *
     * @param callable $callback
     * @param mixed $default
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
     * Returns the index where $needle can be found. If $needle cannot be found, null is returned.
     * $needle can be either a callable or a value.
     *
     * @param mixed $needle
     * @return int|null
     */
    public function findIndex($needle)
    {
        if (is_callable($needle)) {
            foreach ($this->__each() as $idx => $elem) {
                if ($needle($elem) === true) {
                    return $idx;
                }
            }
            return null;
        }
        foreach ($this->__each() as $idx => $elem) {
            if ($needle === $elem) {
                return $idx;
            }
        }
        return null;
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

    /**
     * Returns true if the provided argument is a member of the enumerable.
     * Otherwise returns false.
     *
     * @param mixed $needle
     * @return boolean
     */
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
     * Returns true if no element in the enumerable returns true from the callback.
     *
     * @param callable $callback
     * @return boolean
     */
    public function none(callable $callback)
    {
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === true) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns true if exactly one element in the enumerable returns true from the callback.
     *
     * @param callable $callback
     * @return boolean
     */
    public function one(callable $callback)
    {
        $numMatchingElems = 0;
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === true) {
                if (++$numMatchingElems > 1) {
                    return false;
                }
            }
        }
        return $numMatchingElems === 1;
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
        $notMatching = new EnumerableArray();
        foreach ($this->__each() as $elem) {
            $receiver = ($callback($elem) === true ? $matching : $notMatching);
            $receiver->append($elem);
        }
        return new EnumerableArray([$matching, $notMatching]);
    }

    /**
     * Reduces an enumerable into a single value by applying a callback to each element.
     *
     * @param callable $callback
     * @param mixed $initialValue
     * @return EnumerableArray
     */
    public function reduce(callable $callback, $initialValue = null)
    {
        $memo = $initialValue;
        foreach ($this->__each() as $elem) {
            $memo = $callback($memo, $elem);
        }
        return $memo;
    }

    /**
     * Returns an EnumerableArray containing all elements in the enumerable that returns
     * false from the callback.
     *
     * @param callable $callback
     * @return EnumerableArray
     */
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

    /**
     * Applies a callback on each element in the enumerable in reverse order without
     * returning any value.
     *
     * @param callable $callback
     * @return null
     */
    public function reverseEach(callable $callback)
    {
        $elems = [];
        foreach ($this->__each() as $elem) {
            array_unshift($elems, $elem);
        }
        foreach ($elems as $elem) {
            $callback($elem);
        }
    }

    /**
     * Sorts the elements in the enumerable by using a callback.
     *
     * @param callable $callback
     * @return EnumerableArray
     */
    public function sort(callable $callback)
    {
        $elems = [];
        foreach ($this->__each() as $elem) {
            $elems[] = $elem;
        }
        usort($elems, $callback);
        return new EnumerableArray($elems);
    }

    /**
     * Takes a number of elemens from the enumerable and returns them as an EnumerableArray.
     *
     * @param int $numElems
     * @return EnumerableArray
     */
    public function take($numElems)
    {
        if (!is_int($numElems) || $numElems < 0) {
            throw new \InvalidArgumentException("Argument must be a positive integer or 0.");
        }
        $elems = new EnumerableArray();
        $numTaken = 0;
        foreach ($this->__each() as $elem) {
            if ($numTaken++ == $numElems) {
                break;
            }
            $elems->append($elem);
        }
        return $elems;
    }

    /**
     * Takes elements from the enumerable until the provided callback returns false.
     * The result is returned as an EnumerableArray.
     *
     * @param callable $callback
     * @return EnumerableArray
     */
    public function takeWhile(callable $callback)
    {
        $elems = new EnumerableArray();
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === false) {
                return $elems;
            }
            $elems->append($elem);
        }
        return $elems;
    }
}
