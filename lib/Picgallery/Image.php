<?php

namespace Picgallery;

class Image
{
    private $name;
    private $gallery;
    private $thumbnail_url;
    private $url;
    private $size;
    
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
    public function getGallery() { return $this->gallery; }
    public function setGallery($gallery) { $this->gallery = $gallery; }
    public function getThumbnailUrl() { return $this->thumbnail_url; }
    public function setThumbnailUrl($thumbnail_url) { 
        $this->thumbnail_url = $thumbnail_url;
    }
    public function getUrl() { return $this->url; }
    public function setUrl($url) { $this->url = $url; }
    public function getSize() { return $this->size; }
    public function setSize($size) { $this->size = $size; }
}
