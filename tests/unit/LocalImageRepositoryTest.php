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
}
