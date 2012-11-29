<?php

namespace Picgallery;

interface ImageRepositoryInterface
{
    public function imageExists($image);
    public function uploadImage($title, $mime, $tmp_path);
    public function removeImage($id);
    public function getImages();
}
