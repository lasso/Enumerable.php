<?php

use Lasso3000\Enumerable\Enumerable;

class TopTen
{
    use Enumerable;

    protected function __each()
    {
        for ($i = 10; $i > 0; $i--) {
            yield $i;
        }
    }
}