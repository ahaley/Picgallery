<?php

namespace tests\unit;

class FileHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Picgallery\FileHelper::getFileExtension
     */
	public function ShouldReturnFileExtensionForValidFile()
	{
		$path = '/Path/file.png';

		$fileHelper = new \Picgallery\FileHelper;
		
		$this->assertEquals('png', $fileHelper->getFileExtension($path));
	}
}
