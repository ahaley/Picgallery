<?php

require_once 'Dropbox/DropboxAdapter.php';

use \Picgallery\Dropbox;

class DropboxAdapterTest extends PHPUnit_Framework_TestCase
{
	public function test_Given_Metadata_Will_Return_Image_List()
	{
		// arrange
		$body = new stdClass;
		$body->contents = array(
			(object)array('path' => 'path/file1.png', 'size' => '1.2kb', 'mime_type' => 'image/png'),
			(object)array('path' => 'path/file2.txt', 'size' => '1.2kb', 'mime_type' => 'application/text'),
			(object)array('path' => 'path/file3.png', 'size' => '1.2kb', 'mime_type' => 'image/png')
		);
		$metadata = array('body' => $body);
		$dropbox = $this->getMock('stdObject', array('metaData'));
		$dropbox->expects($this->once())
			->method('metaData')
			->will($this->returnValue($metadata));

		$adapter = new DropboxAdapter($dropbox);

		// act
		$result = $adapter->getImageList();

		// assert
		$this->assertEquals(2, count($result));
		$this->assertEquals('path/file1.png', $result[0]->file);
		$this->assertEquals('path/file3.png', $result[1]->file);
	}
}
