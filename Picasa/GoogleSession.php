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

		$_SESSION['google_token'] = 
			\Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);

		return $_SESSION['google_token'];
	}
}
