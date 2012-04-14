<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../../PictureSync.php';

class PictureSyncTest extends PHPUnit_Framework_TestCase
{
	public function test_Given()
	{
		$image_list = array(
			(object)array('file' => 'image1.png'),
			(object)array('file' => 'image2.png'),
			(object)array('file' => 'image3.png')
		);

		$repository = $this->getMock('IPictureRepository', array('imageExists'));
		
		$repository->expects($this->any())
				   ->method('imageExists')
				   ->will($this->returnCallback('callbackFileExists'));
		
		$pictureSync = new PictureSync($repository);
		$image_list = $pictureSync->updateImageList($image_list);
		
		$this->assertTrue($image_list[0]->inRepository);
		$this->assertFalse($image_list[1]->inRepository);
		$this->assertTrue($image_list[2]->inRepository);
	}

	private function expectFileExists($repository, $file, $does_exist)
	{
		$repository->expects($this->any())
				   ->method('imageExists')
				   ->with($this->equalTo($file))
				   ->will($this->returnValue($does_exist));
	}
}

function callbackFileExists()
{
	$args = func_get_args();
	$map = array(
		'image1.png' => true,
		'image2.png' => false,
		'image3.png' => true
	);
	return $map[$args[0]];
}