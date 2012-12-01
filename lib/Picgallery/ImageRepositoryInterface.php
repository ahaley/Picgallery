<?php

namespace Picgallery;

interface ImageRepositoryInterface
{
    public function uploadImage($name, $type, $path);
    public function removeImage($name);
    public function getImages();
}
