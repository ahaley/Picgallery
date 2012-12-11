<?php

namespace tests\unit;

use Picgallery\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
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
    public function ShouldInstatiateFromPopulateArray()
    {
        $values = array(
            'name' => 'name1',
            'gallery' => 'gallery1',
            'url' => '/url1',
            'thumbnail_url' => '/url1_thumbnail'
        );
        $result = Image::populate($values);
        $this->assertInstanceOf('Picgallery\Image', $result);
        $this->assertEquals('name1', $result->getName());
        $this->assertEquals('gallery1', $result->getGallery());
        $this->assertEquals('/url1', $result->getUrl());
        $this->assertEquals('/url1_thumbnail', $result->getThumbnailUrl());
    }
    
    /**
     * @test
     * @covers Picgallery\Image::setName
     */
    public function ShouldSetName()
    {
        $image = new Image();
        $image->setName('thename1');
        $this->assertEquals('thename1', $image->getName());
    }

    /**
     * @test
     * @covers Picgallery\Image::setThumbnailUrl
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
     * @covers Picgallery\Image::setThumbnailUrl
     */
    public function ShouldSetUrl()
    {
        $image = new Image();
        $url = 'url1';
        $image->setUrl($url);
        $this->assertEquals($url, $image->getUrl());
    }

}
