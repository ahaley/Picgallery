<?php

namespace tests\unit;

use tests\mock;

class ImageStoreTest extends \PHPUnit_Framework_TestCase
{
    private $fileStore;
    private $imageStore;

    public function setUp()
    {
        $localPath = 'local_path';
        $thumbPath = $localPath . '/thumbnails';
        $urlPath = '/url';
        $urlThumb = $urlPath . '/thumbnails';
        $this->fileStore = new mock\FileStore($localPath, $urlPath);
        $this->thumbnailStore = new mock\FileStore($thumbPath, $urlThumb);
        $this->imageStore = new \Picgallery\ImageStore(
            $this->fileStore,
            $this->thumbnailStore
        );
        $this->imageStore->setThumbnailMaker(new mock\ThumbnailMaker());
    }

    /**
     * @test
     * @covers Picgallery\ImageStore::upload
     */
    public function shouldUploadImage()
    {
        $this->imageStore->upload(
            'img.jpg', 'image/jpeg', '/tmp/img.jpg'
        );

        $this->assertTrue($this->fileStore->fileExists('img.jpg'));
    }

    /**
     * @test
     * @covers Picgallery\ImageStore::remove
     */
    public function shouldRemoveImage()
    {
        $name = 'img.jpg';
        $this->imageStore->upload($name, 'image/jpeg', '/tmp/img.jpg');
        $this->imageStore->remove($name);
        $this->assertFalse($this->fileStore->fileExists($name));
    }

    private function _uploadImages($images)
    {
        foreach ($images as $name) {
            $this->imageStore->upload($name, 'image/jpeg', '/path');
        }
    }
}
