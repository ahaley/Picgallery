<?php

namespace tests\integration;

use \Picgallery\ImageRetrieval;
use tests\fixture;

class ImageRetrievalTest extends \PHPUnit_Framework_TestCase
{
    private $retrieval;

    public function setUp()
    {
        $conn = fixture\Database::createDoctrineConnection(); 
        $this->retrieval = new ImageRetrieval($conn);
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImages
     */
    public function listImagesShouldProduceCorrectNumberOfImageObjects()
    {
        $images = $this->retrieval->getImages();
        $this->assertEquals(3, count($images));
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImages
     */
    public function listImagesShouldCorrectlyRetrieveImageUrl()
    {
        $images = $this->retrieval->getImages();
        $this->assertEquals('/gallery1/image1.jpg', $images[0]->getUrl());
        $this->assertEquals('/gallery1/image2.jpg', $images[1]->getUrl());
        $this->assertEquals('/gallery2/img1.jpg', $images[2]->getUrl());
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImages
     */
    public function listImagesShouldCorrectlyRetrieveThumbnailUrl()
    {
        $images = $this->retrieval->getImages();
        $this->assertEquals('/gallery1/thumb/image1.jpg',
            $images[0]->getThumbnailUrl());
        $this->assertEquals('/gallery1/thumb/image2.jpg',
            $images[1]->getThumbnailUrl());
        $this->assertEquals('/gallery2/thumb/img1.jpg',
            $images[2]->getThumbnailUrl());
    }
}
