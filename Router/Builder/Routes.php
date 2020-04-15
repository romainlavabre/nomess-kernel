<?php

namespace NoMess\Router\Builder;

class Routes{
    private $url;

    private $controller;

    private $get;

    private $post;



    public function getUrl() : string
    {
        return $this->url;
    }

    public function getController() : string 
    {
        return $this->controller;
    }

    public function getGet() : ?string
    {
        return $this->get;  
    }

    public function getPost() : ?string
    {
        return $this->post;
    }

    public function setUrl(string $setter) : void
    {
        $this->url = $setter;
    }

    public function setController(string $setter) : void
    {
        $this->controller = $setter;
    }

    public function setGet(string $setter) : void
    {
        $this->get = $setter;
    }

    public function setPost(string $setter) : void
    {
        $this->post = $setter;
    }
}