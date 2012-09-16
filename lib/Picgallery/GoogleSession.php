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

class AuthSubGoogleSession extends GoogleSession
{
	public function getGoogleToken()
	{
		if (isset($_SESSION['google_token']))
			return $_SESSION['google_token'];

		if (!isset($_GET['token']))
			return null;

        try {
            $_SESSION['google_token'] = 
                \Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);
        }
        catch (\Zend_Gdata_App_AuthException $ex) {
            unset($_GET['token']);
            return null;
        }

		return $_SESSION['google_token'];
    }
    
    public function hasGoogleToken()
    {
        return $this->getGoogleToken() !== null;
    }

    public function getHttpClient()
    {
        $googleToken = $this->getGoogleToken();
        if ($googleToken === NULL) {
            return NULL;
        }
		return \Zend_Gdata_AuthSub::getHttpClient($googleToken);
    }

    public function getAuthUrl($nextUrl = null)
	{
        if ($nextUrl === NULL) {
            $nextUrl = 'http://' . $_SERVER['SERVER_NAME'] .
                $_SERVER['REQUEST_URI'];
        }
		$scope = 'http://picasaweb.google.com/data';
		$secure = 0;
		$session = 1;

        $authSubAdapter = $this->authSubAdapter;
		return $authSubAdapter::getAuthSubTokenUri(
            $nextUrl, $scope, $secure, $session
        );
	}

    private $authSubAdapter = '\Zend_Gdata_AuthSub';

    public function setAuthSubAdapter($authSubAdapter)
    {
        $this->authSubAdapter = $authSubAdapter;
    }
}
