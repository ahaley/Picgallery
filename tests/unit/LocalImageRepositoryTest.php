<?php

namespace tests\unit;

use tests\mock;

class LocalImageRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $fileStore;
    private $repository;

    public function setUp()
    {
        $localPath = 'local_path';
        $thumbPath = $localPath . '/thumbnails';
        $urlPath = '/url';
        $urlThumb = $urlPath . '/thumbnails';
        $this->fileStore = new mock\FileStore($localPath, $urlPath);
        $this->thumbnailStore = new mock\FileStore($thumbPath, $urlThumb);
        $this->repository = new \Picgallery\LocalImageRepository(
            $this->fileStore,
            $this->thumbnailStore
        );
        $this->repository->setThumbnailMaker(new mock\ThumbnailMaker());
    }

    /**
     * @test
     */
    public function shouldInstantiateObjectOfCorrectType()
    {
        $this->assertInstanceOf(
            '\Picgallery\LocalImageRepository', $this->repository);
    }

    /**
     * @test
     * @covers Picgallery\LocalImageRepository::imageExists
     */
    public function shouldReturnFalseForNonexistantImageExist()
    {
        $this->assertFalse($this->repository->imageExists('nonexistant.png'));
    }

    /**
     * @test
     * @covers Picgallery\LocalImageRepository::uploadImage
     */
    public function shouldUploadImage()
    {
        $this->repository->uploadImage(
            'img.jpg', 'image/jpeg', '/tmp/img.jpg'
        );

        $this->assertTrue($this->repository->imageExists('img.jpg'));
    }

    /**
     * @test
     * @covers Picgallery\LocalImageRepository::removeImage
     */
    public function shouldRemoveImage()
    {
        $name = 'img.jpg';
        $this->repository->uploadImage($name, 'image/jpeg', '/tmp/img.jpg');
        $this->repository->removeImage($name);
        $this->assertFalse($this->repository->imageExists($name));
    }

    private function _uploadImages($images)
    {
        foreach ($images as $name) {
            $this->repository->uploadImage($name, 'image/jpeg', '/path');
        }
    }

    /**
     * @test
     * @covers Picgallery\LocalImageRepository::getImages
     */
    public function listImagesShouldProduceCorrectNumberOfImageObjects()
    {
        $this->_uploadImages(array('img1.jpg', 'img2.jpg', 'img3.jpg'));
        $images = $this->repository->getImages();
        $this->assertEquals(3, count($images));
    }

    /**
     * @test
     * @covers Picgallery\LocalImageRepository::getImages
     */
    public function listImagesShouldCorrectlyRetrieveImageUrl()
    {
        $this->_uploadImages(array('img1.jpg', 'img2.jpg', 'img3.jpg'));
        $images = $this->repository->getImages();
        $this->assertEquals('/url/img1.jpg', $images[0]->getUrl());
        $this->assertEquals('/url/img2.jpg', $images[1]->getUrl());
        $this->assertEquals('/url/img3.jpg', $images[2]->getUrl());
    }

    /**
     * @test
     * @covers Picgallery\LocalImageRepository::getImages
     */
    public function listImagesShouldCorrectlyRetrieveThumbnailUrl()
    {
        $this->_uploadImages(array('img1.jpg', 'img2.jpg', 'img3.jpg'));
        $images = $this->repository->getImages();
        $this->assertEquals('/url/thumbnails/img1.jpg',
            $images[0]->getThumbnailUrl());
        $this->assertEquals('/url/thumbnails/img2.jpg',
            $images[1]->getThumbnailUrl());
        $this->assertEquals('/url/thumbnails/img3.jpg',
            $images[2]->getThumbnailUrl());
    }
}
