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
        $this->assertEquals('/url/img1.jpg', $images[0]->getUrl());
        $this->assertEquals('/url/img2.jpg', $images[1]->getUrl());
        $this->assertEquals('/url/img3.jpg', $images[2]->getUrl());
    }

    /**
     * @test
     * @covers Picgallery\ImageRetrieval::getImages
     */
    public function listImagesShouldCorrectlyRetrieveThumbnailUrl()
    {
        $images = $this->retrieval->getImages();
        $this->assertEquals('/url/thumbnails/img1.jpg',
            $images[0]->getThumbnailUrl());
        $this->assertEquals('/url/thumbnails/img2.jpg',
            $images[1]->getThumbnailUrl());
        $this->assertEquals('/url/thumbnails/img3.jpg',
            $images[2]->getThumbnailUrl());
    }
}
