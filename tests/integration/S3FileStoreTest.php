<?php

namespace tests\integration;

use Picgallery\S3FileStore;

require_once LIBRARY_PATH . '/config.php';

class S3FileStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers \Picgallery\S3FileStore::upload
     */
    public function ShouldUploadFile()
    {
        $store = new S3FileStore('picgallery_tests', 'integration', '');
        $store->uploadFile('ahaley/thumbnails/test2', '/Users/ahaley/test');
    }
    
    /**
     * @test
     * @covers \Picgallery\S3FileStore::remove
     */
    public function ShouldRemoveFile()
    {
        $store = new S3FileStore('picgallery_tests', 'integration', '');
        $store->removeFile('test2');
    }
}
