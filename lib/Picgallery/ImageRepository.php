<?php

namespace Picgallery;

interface ImageRepository
{
	public function imageExists($image);
	public function uploadImage($title, $mime, $file_path);
	public function removeImage($id);
    public function getImages();
    public function getAlbumRepository();
}
