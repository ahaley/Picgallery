<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery.php';
require_once 'PicgalleryTestContext.php';
require_once 'fakes/ImageRepositoryFake.php';

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
		$googleSession = new \Picgallery\AuthSubGoogleSession($username);
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

        $imageArray = array(
            'file1.png' => false,
            'file2.png' => true,
            'file3.png' => false,
            'file4.png' => true
        );

        $context->wireImageRepoWithImageArray($imageArray);
        $context->wireDropboxWithImageArray($imageArray);

		$picgallery = new Picgallery\Picgallery(
            $context->getImageRepo(),
			$context->getDropbox()
		);
		
		// act
		$imageList = $picgallery->getImageList();

		// assert
        $context->assertImageIsInRepository($imageList, 'file2.png');
        $context->assertImageIsInRepository($imageList, 'file4.png');
        $context->assertImageIsNotInRepository($imageList, 'file1.png');
        $context->assertImageIsNotInRepository($imageList, 'file3.png');
	}
}
