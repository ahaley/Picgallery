<?php

namespace tests\integration;

use \Picgallery\FileStore;
use \Picgallery\LocalImageRepository;

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
        $this->fileStore = new FileStore($localPath, $urlPath);
        $this->thumbnailStore = new FileStore($thumbPath, $urlThumb);
        $this->repository = new LocalImageRepository(
            $this->fileStore,
            $this->thumbnailStore
        );
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
            'teapot.jpg', 'image/jpeg', 'images/teapot.jpg'
        );
        
        $this->assertTrue($this->repository->imageExists('teapot.jpg'));
    }

    /**
     * @test
     * @covers Picgallery\LocalImageRepository::removeImage
     */
    public function shouldRemoveImage()
    {
        $this->repository->uploadImage(
            'teapot.jpg', 'image/jpeg', 'images/teapot.jpg'
        );
        $this->repository->removeImage('teapot.jpg');
        $this->assertFalse($this->repository->imageExists('teapot.jpg'));
    }
}
