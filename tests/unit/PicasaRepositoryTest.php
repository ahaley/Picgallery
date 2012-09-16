<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/PicasaRepository.php';
require_once 'Picgallery/GoogleSession.php';

use Picgallery\PicasaRepository;

class PicasaRepositoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function CreateShouldReturnNullWithInvalidSession()
	{
		// arrange
        $username = 'user1@gmail.com';
		global $_SESSION, $_GET;
		unset($_SESSION['google_token']);
		unset($_GET['token']);
		$googleSession = new \Picgallery\AuthSubGoogleSession($username);

		// act
		$result = PicasaRepository::create($googleSession);

		// assert
		$this->assertNull($result);
	}
}
