<?php

namespace tests\integration;

use \Picgallery\ImageRetrieval;
use tests\fixture;

class ImageRetrievalTest extends \PHPUnit_Framework_TestCase
{
    private $conn;

    public function setUp()
    {
        $this->conn = fixture\Database::createDoctrineConnection(); 
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImages
     */
    public function listImagesShouldProduceCorrectNumberOfImageObjects()
    {
        $retrieval = new \Picgallery\ImageRetrieval($this->conn, 'gallery1');
        $images = $retrieval->getImages();
        $this->assertEquals(2, count($images));
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImages
     */
    public function listImagesShouldCorrectlyRetrieveImageUrl()
    {
        $retrieval = new \Picgallery\ImageRetrieval($this->conn, 'gallery1');
        $images = $retrieval->getImages();
        $this->assertEquals('/gallery1/image1.jpg', $images[0]->getUrl());
        $this->assertEquals('/gallery1/image2.jpg', $images[1]->getUrl());
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImages
     */
    public function listImagesShouldCorrectlyRetrieveThumbnailUrl()
    {
        $retrieval = new \Picgallery\ImageRetrieval($this->conn, 'gallery1');
        $images = $retrieval->getImages();
        $this->assertEquals('/gallery1/thumb/image1.jpg',
            $images[0]->getThumbnailUrl());
        $this->assertEquals('/gallery1/thumb/image2.jpg',
            $images[1]->getThumbnailUrl());
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImage
     */
    public function getImageShouldRetrieveSingleImage()
    {
        $retrieval = new \Picgallery\ImageRetrieval($this->conn, 'gallery1');
        $result = $retrieval->getImage('image1.jpg');
        $this->assertInstanceOf('\Picgallery\Image', $result);
        $this->assertEquals('image1.jpg', $result->getName());
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImage
     */
    public function getImageShouldReturnNullForNonexistantImage()
    {
        $retrieval = new \Picgallery\ImageRetrieval($this->conn, 'gallery1');
        $result = $retrieval->getImage('doesnotexist.jpg');
        $this->assertNull($result);
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::record
     */
    public function recordShouldStoreImageModelInDatabase()
    {
        $image = \Picgallery\Image::populate(array(
            'name' => 'record1',
            'gallery' => 'gallery1',
        ));

        $retrieval = new \Picgallery\ImageRetrieval($this->conn, 'gallery1');
        $retrieval->record($image);

        $result = $retrieval->getImage('record1');
        
        $this->assertEquals($image, $result);
    }
}
