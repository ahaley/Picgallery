<?php

namespace tests\integration;

class ImageStoreTest extends \PHPUnit_Framework_TestCase
{
    private $imageStore;

    public function setUp()
    {
        $this->imageStore = \Picgallery\ImageStoreFactory::createFileBacked(
            '/tmp', '/gallery', 'gallery1'
        );
    }

    public function tearDown()
    {
        if (file_exists('/tmp/gallery1')) {
            if (file_exists('/tmp/gallery1/teapot1.jpg'))
                unlink('/tmp/gallery1/teapot1.jpg');
            if (file_exists('/tmp/gallery1/thumbnails')) {
                if (file_exists('/tmp/gallery1/thumbnails/teapot1.jpg')) {
                    unlink('/tmp/gallery1/thumbnails/teapot1.jpg');
                }
                rmdir('/tmp/gallery1/thumbnails');
            }
            rmdir('/tmp/gallery1');
        }
    }

    /**
     * @test
     * @covers Picgallery\ImageStore::upload
     */
    public function shouldUpload()
    {

        $image = $this->imageStore->upload('teapot1.jpg', 'image/jpeg',
            ROOT_PATH . '/tests/images/teapot.jpg'
        );

        $this->assertInstanceOf('\Picgallery\Image', $image);
        
        $this->assertEquals('/gallery/gallery1/teapot1.jpg', $image->getUrl());
        $this->assertEquals('/gallery/gallery1/thumbnails/teapot1.jpg',
            $image->getThumbnailUrl());

        $this->assertTrue(file_exists('/tmp/gallery1/teapot1.jpg'));
        $this->assertTrue(file_exists('/tmp/gallery1/thumbnails/teapot1.jpg'));

    }
}
