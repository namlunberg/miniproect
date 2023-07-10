<?php

namespace Services\Requests;

class Get extends BaseGlobalsArray
{
    public function __construct(array $get = [])
    {
        $this->array = $get;
    }
}
