<?php

namespace Picgallery;

interface ImageRepositoryInterface
{
    public function uploadImage($title, $mime, $tmp_path);
    public function removeImage($id);
    public function getImages();
}
