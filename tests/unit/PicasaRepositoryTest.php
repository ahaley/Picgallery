<?php

namespace tests\unit;

class PicasaRepositoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
     * @covers Picgallery\PicasaRepository::create
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
		$result = \Picgallery\PicasaRepository::create($googleSession);

		// assert
		$this->assertNull($result);
	}
}
