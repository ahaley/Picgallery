<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/Image.php';

use Picgallery\Image;

class ImageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function ShouldInstantiateImage()
	{
        $image = new Image();
        $this->assertInstanceOf('Picgallery\Image', $image);
    }
    
    /**
     * @test
     */
    public function ShouldSetName()
    {
    	$image = new Image();
    	$image->setName('thename1');
    	$this->assertEquals('thename1', $image->getName());
    }

    /**
     * @test
     */
    public function ShouldSetThumbnailUrl()
    {
        $image = new Image();
        $thumbnail = "thumb1";
        $image->setThumbnailUrl($thumbnail);
        $this->assertEquals($thumbnail, $image->getThumbnailUrl());
    }

    /**
     * @test
     */
    public function ShouldSetUrl()
    {
        $image = new Image();
        $url = 'url1';
        $image->setUrl($url);
        $this->assertEquals($url, $image->getUrl());
    }

}
