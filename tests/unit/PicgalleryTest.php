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
		global $_SESSION;
		unset($_SESSION['picasa_token']);
		unset($_GET['token']);

		// act
		$googleSession = new \Picgallery\GoogleSession();
		$result = \Picgallery\Picgallery::create(null, null,
			'user1@gmail.com',
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
		$imageList = array();
		$context->wireImageSync($imageList);
		$picgallery = new \Picgallery\Picgallery(
			$context->getPicasa(),
			$context->getDropbox(),
			$context->getPictureSync()
		);
		
		// act
		$pictureList = $picgallery->getPictureList();

		// assert
		$this->assertEquals($imageList, $pictureList);
	}
}
