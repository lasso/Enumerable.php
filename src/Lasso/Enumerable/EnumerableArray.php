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

class EnumerableArray implements \ArrayAccess
{
    use Enumerable;

    protected $elems;

    public function __construct(array $elems = [])
    {
        $this->elems = $elems;
    }

    public function append($elem)
    {
        $this->elems[] = $elem;
    }

    public function toArray()
    {
        return $this->elems;
    }

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

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfRangeException();
        }
        return $this->elems[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfRangeException();
        }
        $this->elems[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfRangeException();
        }
        unset($this->elems[$offset]);
    }

    protected function __each()
    {
        foreach ($this->elems as $elem) {
            yield $elem;
        }
    }
}
