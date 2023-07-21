<?php

namespace Services\Requests;

class Request
{
    private Get $get;
    private Post $post;
    private Server $server;
    private Session $session;
    private Cookies $cookies;

    public function __construct(array $get, array $post, array $server)
    {
        $this->setGet(new Get($get));
        $this->setPost(new Post($post));
        $this->setServer(new Server($server));
        $this->setSession(new Session());
        $this->setCookies(new Cookies());
    }

    public function getGet(): Get
    {
        return $this->get;
    }

    public function setGet(Get $get): void
    {
        $this->get = $get;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    public function setServer(Server $server): void
    {
        $this->server = $server;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    public function getCookies(): Cookies
    {
        return $this->cookies;
    }

    public function setCookies(Cookies $cookies): void
    {
        $this->cookies = $cookies;
    }

    public function isHTTPS(): bool
    {
        if (!empty($this->getServer()->getField('HTTPS'))) {
            $result =  true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function getCurrentUrl(): string
    {
        return ($this->isHTTPS() ? 'https' : 'http') . '://' . $this->getServer()->getField('HTTP_HOST') . $this->getServer()->getField('REQUEST_URI');
    }

}