<?php

require_once 'PicasaAdapter.php';
require_once 'GoogleSession.php';

class PicasaAdapterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function Create_Without_Google_Session_Will_Return_Auth_Url()
	{
		// arrange
		global $_SESSION, $_GET;
		unset($_SESSION['google_token']);
		unset($_GET['token']);
		$googleSession = new \Picgallery\GoogleSession();

		// act
		$picasaAdapter = \Picgallery\PicasaAdapter::create(
			'user1@gmail.com',
			$googleSession
		);

		// assert
		$this->assertNull($picasaAdapter);
	}
}
