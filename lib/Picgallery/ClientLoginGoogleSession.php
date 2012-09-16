<?php

namespace Picgallery;

class ClientLoginGoogleSession extends GoogleSession
{
    private $password;

    public function __construct($username, $password)
    {
        parent::__construct($username);
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getHttpClient()
    {
        $username = $this->getUsername();
        $password = $this->getPassword();
        $service = \Zend_Gdata_Photos::AUTH_SERVICE_NAME;

        $client = \Zend_Gdata_ClientLogin::getHttpClient(
            $username, $password, $service
        );

        return $client;
    }
}

