<?php

// Lasso/Enumerable - A implementation of ruby's Enumerable module written in PHP.
// Copyright (C) 2016  Lars Olsson <lasso@lassoweb.se>
//
// This file is part of Lasso/Enumerable.
//
// Lasso/Enumerable is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Lasso/Enumerable is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with Lasso/Enumerable. If not, see <http://www.gnu.org/licenses/>.

namespace Lasso\Enumerable;

trait Enumerable
{
    protected abstract function __each();

    public function all(callable $callback)
    {
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === false) {
                return false;
            }
        }
        return true;
    }

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

    public function count(callable $callback = null)
    {
        $num_elems = 0;
        if ($callback) {
            foreach ($this->__each() as $elem) {
                if ($callback($elem)) {
                    $num_elems++;
                }
            }
        }
        else {
            foreach ($this->__each() as $elem) {
                $num_elems++;
            }
        }
        return $num_elems;
    }

    public function find(callable $callback, $default = null)
    {
        foreach ($this->__each() as $elem) {
            if ($callback($elem) === true) {
                return $elem;
            }
        }
        return $default;
    }

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
}
