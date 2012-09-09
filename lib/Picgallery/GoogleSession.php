<?php

namespace Picgallery;

class GoogleSession
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
		return \Zend_Gdata_AuthSub::getAuthSubTokenUri(
            $nextUrl, $scope, $secure, $session
        );
	}
}
