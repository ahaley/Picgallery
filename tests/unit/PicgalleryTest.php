<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery.php';
require_once 'PicgalleryTestContext.php';

class PicgalleryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function Create_Without_Google_Session_Token_Will_Return_AuthUrl()
	{
		// arrange
        $username = 'user1@gmail.com';
		global $_SESSION;
		unset($_SESSION['picasa_token']);
		unset($_GET['token']);

		// act
		$googleSession = new \Picgallery\GoogleSession($username);
		$result = \Picgallery\Picgallery::create(null, null,
			$googleSession,
			'http://localhost/uri'
		);

		// assert
		$this->assertEquals(Picgallery\Picgallery::GoogleError, $result->error);
		$this->assertTrue(
			stripos($result->authUrl, 'https://') == 0
		);
	}

	/**
	 * @test
	 */
	public function PictureList_Will_Get_List_From_Dropbox_And_Filter_With_Picasa()
	{
		// arrange
		$context = new PicgalleryTestContext($this);
		$expectedImageList = array();
		$context->wireImageSync($expectedImageList);
		$picgallery = new \Picgallery\Picgallery(
			$context->getPicasa(),
			$context->getDropbox(),
			$context->getImageSyncer()
		);
		
		// act
		$imageList = $picgallery->getImageList();

		// assert
		$this->assertEquals($expectedImageList, $imageList);
	}
}
