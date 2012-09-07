<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'ImageSyncer.php';


class ImageSyncerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function Map_Image_Existence_Will_Return_In_Repository_Mapping()
	{
        $this->GivenAImageSyncer();
        $this->WhenICallMapImageExistence();
        $this->ThenIShouldSeeImagesMappedByExistence();
    }

    private $image_syncer;

    private function GivenAImageSyncer()
    {
    	$repository = $this->getMock('Picgallery\ImageRepository');
		
		$repository->expects($this->any())
				   ->method('imageExists')
				   ->will($this->returnCallback('callbackFileExists'));

        $image_syncer = new Picgallery\ImageSyncer($repository);
        $this->image_syncer = $image_syncer;
    }

    private $mapped_list;

    private function WhenICallMapImageExistence()
    {
        $image_list = array(
			(object)array('file' => 'image1.png'),
			(object)array('file' => 'image2.png'),
			(object)array('file' => 'image3.png')
		);
	
		$this->mapped_list = $this->image_syncer->mapImageExistence($image_list);
    }

    private function ThenIShouldSeeImagesMappedByExistence()
    {
        $mapped_list = $this->mapped_list;
        $this->assertTrue($mapped_list[0]->inRepository);
		$this->assertFalse($mapped_list[1]->inRepository);
		$this->assertTrue($mapped_list[2]->inRepository);
    }
}

/**
 * callback to handle emulating ImageRepository:::imageExists
 */

function callbackFileExists()
{
	$map = array(
		'image1.png' => true,
		'image2.png' => false,
		'image3.png' => true
	);
	$args = func_get_args();
	return $map[$args[0]];
}
