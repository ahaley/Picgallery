<?php

class PicgalleryTestContext
{
	private $_testCase;
	private $_dropbox;
	private $_picasa;
	private $_imageSyncer;

	public function getDropbox() { return $this->_dropbox; }
	public function getPicasa() { return $this->_picasa; }
	public function getImageSyncer() { return $this->_imageSyncer; }

	public function __construct($testCase)
	{
		$this->_testCase = $testCase;
		$this->_picasa = $testCase->getMock('\Picgallery\PicasaRepository');
		$this->_dropbox = $testCase->getMock('\Picgallery\DropboxAdapter', 
			array('getImageList'));
		$this->_imageSyncer = $testCase->getMock('\Picgallery\ImageSyncer',
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
		$this->getImageSyncer()->expects($testCase->once())
			->method('mapImageExistence')
			->with($testCase->equalTo($imageList))
			->will($testCase->returnValue($imageList));
	}
}
