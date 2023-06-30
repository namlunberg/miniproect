<?php

namespace src\classes;

class Requests
{
    private array $get;
    private array $post;
    private array $server;

    public function __construct(array $get, array $post, array $server)
    {
        $this->setGet($get);
        $this->setPost($post);
        $this->setServer($server);
    }

    public function getPostField(string $field): mixed
    {
        return $this->getPost()[$field] ?? NULL;
    }

    public function getGetField(string $field): mixed
    {
        return $this->getGet()[$field] ?? NULL;
    }


    public function getServerField(string $field): mixed
    {
        return $this->getServer()[$field] ?? NULL;
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * @param array $server
     */
    public function setServer(array $server): void
    {
        $this->server = $server;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @param array $get
     */
    public function setGet(array $get): void
    {
        $this->get = $get;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @param array $post
     */
    public function setPost(array $post): void
    {
        $this->post = $post;
    }


}