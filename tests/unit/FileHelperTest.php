<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'FileHelper.php';

class FileHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_Given_Valid_File_Returns_Correct_Extension()
	{
		$path = '/Path/file.png';

		$fileHelper = new Picgallery\FileHelper;
		
		$this->assertEquals('png', $fileHelper->getFileExtension($path));
	}
}
