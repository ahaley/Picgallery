<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/PicasaRepository.php';
require_once 'Picgallery/GoogleSession.php';

class PicasaRepositoryTest extends PHPUnit_Framework_TestCase
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
		$result = \Picgallery\PicasaRepository::create(
			'user1@gmail.com',
			$googleSession
		);

		// assert
		$this->assertNull($result);
	}
}
