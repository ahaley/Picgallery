<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/DropboxAdapter.php';

use Picgallery\DropboxAdapter;

class DropboxAdapterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function ShouldExtractImageListFromDropboxApi()
	{
		// arrange
		$adapter = new DropboxAdapter($this->createDropboxObject());

		// act
		$result = $adapter->getImageList();

		// assert
		$this->assertEquals(2, count($result));
		$this->assertEquals('path/file1.png', $result[0]->file);
		$this->assertEquals('path/file3.png', $result[1]->file);
	}
	
	private function createDropboxObject()
	{
		$dropbox = $this->getMock('stdObject', array('metaData'));
		$dropbox->expects($this->once())
			->method('metaData')
			->will($this->returnValue($this->createMetadata()));
		return $dropbox;
	}
	
	private function createMetadata()
	{
		$body = new stdClass;
		$body->contents = array(
			(object)array(
				'path' => 'path/file1.png',
				'size' => '1.2kb',
				'mime_type' => 'image/png'
			),
			(object)array(
				'path' => 'path/file2.txt',
				'size' => '1.2kb',
				'mime_type' => 'application/text'
			),
			(object)array(
				'path' => 'path/file3.png',
				'size' => '1.2kb',
				'mime_type' => 'image/png'
			)
		);
		$metadata = array('body' => $body);
		return $metadata;
	}
}
