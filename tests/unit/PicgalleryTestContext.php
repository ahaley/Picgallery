<?php

class PicgalleryTestContext
{
	private $_testCase;
	private $_dropbox;
	private $_picasa;
	private $_pictureSync;

	public function getDropbox() { return $this->_dropbox; }
	public function getPicasa() { return $this->_picasa; }
	public function getPictureSync() { return $this->_pictureSync; }

	public function __construct($testCase)
	{
		$this->_testCase = $testCase;
		$this->_picasa = $testCase->getMock('\Picgallery\PicasaAdapter');
		$this->_dropbox = $testCase->getMock('\Picgallery\DropboxAdapter', 
			array('getImageList'));
		$this->_pictureSync = $testCase->getMock('\Picgallery\PictureSync',
			array('updateImageList'),
			array($this->_picasa)
		);
	}
	
	public function wireImageSync($imageList)
	{
		$testCase = $this->_testCase;
		$this->getDropbox()->expects($testCase->once())
			->method('getImageList')
			->will($testCase->returnValue($imageList));
		$this->getPictureSync()->expects($testCase->once())
			->method('updateImageList')
			->with($testCase->equalTo($imageList))
			->will($testCase->returnValue($imageList));
	}
}