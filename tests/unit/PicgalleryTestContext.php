<?php

require_once 'fakes/ImageRepositoryFake.php';
require_once 'Picgallery/ImageSyncer.php';

class PicgalleryTestContext
{
	private $_testCase;
	private $_dropbox;
	private $_imageRepo;

	public function getDropbox() { return $this->_dropbox; }
	public function getImageRepo() { return $this->_imageRepo; }

	public function __construct($testCase)
	{
		$this->_testCase = $testCase;
        $this->_imageRepo = new Picgallery\ImageRepositoryFake();
		$this->_dropbox = $testCase->getMock('\Picgallery\DropboxAdapter', 
			array('getImageList'));
	}

    public function wireImageRepoWithImageArray($imageArray)
    {
        $this->_imageRepo->wireImageArray($imageArray);
    }

    public function wireDropboxWithImageArray($imageArray)
    {
        $images = array_keys($imageArray);
        $imagesList = array();
        foreach ($images as $image) {
            $imagesList[] = (object)array(
                'file' => $image,
                'size' => 1,
                'mime_type' => 'image/png'
            );
        }

        $this->_dropbox->expects($this->_testCase->once())
            ->method('getImageList')
            ->will($this->_testCase->returnValue($imagesList));
    }

    private function _getImage($imageList, $imageName)
    {
        foreach ($imageList as $image) {
            if ($imageName == $image->file)
                return $image;
        }
        return null;
    }

    public function assertImageIsInRepository($imageList, $imageName)
    {
        $image = $this->_getImage($imageList, $imageName);
        $this->_testCase->assertNotNull($image);
        $this->_testCase->assertTrue($image->inRepository);
    }

    public function assertImageIsNotInRepository($imageList, $imageName)
    {
        $image = $this->_getImage($imageList, $imageName);
        $this->_testCase->assertNotNull($image);
        $this->_testCase->assertFalse($image->inRepository);
    }
}
