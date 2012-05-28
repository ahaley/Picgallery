<?php

class PicgalleryTestContext
{
	private $_testCase;
	private $_dropbox;
	private $_picasa;
	private $_pictureSyncer;

	public function getDropbox() { return $this->_dropbox; }
	public function getPicasa() { return $this->_picasa; }
	public function getPictureSyncer() { return $this->_pictureSyncer; }

	public function __construct($testCase)
	{
		$this->_testCase = $testCase;
		$this->_picasa = $testCase->getMock('\Picgallery\PicasaAdapter');
		$this->_dropbox = $testCase->getMock('\Picgallery\DropboxAdapter', 
			array('getImageList'));
		$this->_pictureSyncer = $testCase->getMock('\Picgallery\PictureSyncer',
			array('mapImageExistence'),
			array($this->_picasa)
		);
	}
	
	public function wireImageSync($imageList)
	{
		$testCase = $this->_testCase;
		$this->getDropbox()->expects($testCase->once())
			->method('getImageList')
			->will($testCase->returnValue($imageList));
		$this->getPictureSyncer()->expects($testCase->once())
			->method('mapImageExistence')
			->with($testCase->equalTo($imageList))
			->will($testCase->returnValue($imageList));
	}
}
