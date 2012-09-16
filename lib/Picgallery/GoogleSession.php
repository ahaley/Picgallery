<?php

namespace Picgallery;

abstract class GoogleSession
{
    private $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    abstract public function getHttpClient();
}

