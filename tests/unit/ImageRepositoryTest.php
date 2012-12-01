<?php

namespace tests\unit;

use tests\mock;

class ImageRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $fileStore;
    private $repository;

    public function setUp()
    {
        $this->store = $this->getMock('\Picgallery\ImageStoreInterface');
        $this->retrieval = $this->getMock(
            '\Picgallery\ImageRetrievalInterface');
        $this->repository = new \Picgallery\ImageRepository(
            $this->store, $this->retrieval
        );
    }

    /**
     * @test
     * @covers \Picgallery\ImageRepository::uploadImage
     */
    public function uploadImageForwardsToStore()
    {
        $this->store->expects($this->once())
            ->method('upload')
            ->with(
                $this->equalTo('img.jpg'),
                $this->equalTo('image/jpeg'),
                $this->equalTo('/tmp/img.jpg')
            );
                

        $this->repository->uploadImage(
            'img.jpg', 'image/jpeg', '/tmp/img.jpg'
        );
    }

    /**
     * @test
     * @covers Picgallery\ImageRepository::removeImage
     */
    public function removeImageShouldDisableFromRetrieval()
    {
        $name = 'img.jpg';
        $this->retrieval->expects($this->once())
            ->method('disable')
            ->with($this->equalTo($name));

        $this->repository->removeImage($name);
    }

    /**
     * @test
     * @covers Picgallery\ImageRepository::getImages
     */
    public function listImagesShouldProduceCorrectNumberOfImageObjects()
    {
        $images = array();
        $this->retrieval->expects($this->once())
            ->method('getImages')
            ->will($this->returnValue($images));

        $result = $this->repository->getImages();
        $this->assertTrue($images === $result);
    }

}
