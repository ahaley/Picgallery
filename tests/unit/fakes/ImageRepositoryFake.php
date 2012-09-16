<?php

namespace Picgallery;

require_once 'Picgallery/ImageRepository.php';

class ImageRepositoryFake implements ImageRepository
{
    private $images;

    public function wireImageArray($images)
    {
        $this->images = $images;
    }

    public function imageExists($image)
    {
        if (!array_key_exists($image, $this->images))
            return false;
        return $this->images[$image];
    }

    public function uploadImage($title, $mime, $file_path)
    {
    }

    public function removeImage($id)
    {
    }

    public function getImages()
    {
    }
}

