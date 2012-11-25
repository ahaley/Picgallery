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
     */
    public function shouldReturnFalseForNonexistantImageExist()
    {
        $this->assertFalse($this->repository->imageExists('nonexistant.png'));
    }

    /**
     * @test
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
     */
    public function shouldRemoveImage()
    {
        $name = 'img.jpg';
        $this->repository->uploadImage($name, 'image/jpeg', '/tmp/img.jpg');
        $this->repository->removeImage($name);
        $this->assertFalse($this->repository->imageExists($name));
    }

    /**
     * @test
     */
    public function shouldListImages()
    {
        $files = array('img1.jpg', 'img2.jpg', 'img3.jpg');
        foreach ($files as $name) {
            $this->repository->uploadImage($name, 'image/jpeg', '/path');
        }
        $images = $this->repository->getImages();


        $this->assertEquals(3, count($images));

    }
}
