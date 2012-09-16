<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/FileHelper.php';

class FileHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
	public function ShouldReturnFileExtensionForValidFile()
	{
		$path = '/Path/file.png';

		$fileHelper = new Picgallery\FileHelper;
		
		$this->assertEquals('png', $fileHelper->getFileExtension($path));
	}
}
