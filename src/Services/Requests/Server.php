<?php

namespace Services\Requests;

class Server extends BaseGlobalsArray
{
    public function __construct(array $server=[])
    {
        $this->array=$server;
    }
}