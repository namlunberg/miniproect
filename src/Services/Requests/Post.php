<?php

namespace Services\Requests;

class Post extends BaseGlobalsArray
{
    public function __construct(array $post=[])
    {
        $this->array=$post;
    }
}