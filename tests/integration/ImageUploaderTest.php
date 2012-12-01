<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/ImageUploader.php';

use Picgallery\ImageUploader;

class ImageUploaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ShouldInstantiateImageUploader()
    {
        $uploader = new ImageUploader(array());
    }

    /**
     * @test
     */
    public function ShouldUploadFile()
    {
        $path = 'source/thefile';
        $_file = array(
            'image' => array(
                'name' => $path,
                'type' => 'text/plain',
                'size' => filesize($path),
                'tmpname' => $path,
            )
        );
        $uploader = new ImageUploader($_file);
        $uploader->upload();
    }
}
