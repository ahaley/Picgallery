<?php

namespace Picgallery;

class Image
{
    private $name;
    private $gallery;
    private $thumbnail_url;
    private $url;
    private $size;
    
    public function setName($name) { $this->name = $name; }
    public function getName() { return $this->name; }
    public function setGallery($gallery) { $this->gallery = $gallery; }
    public function getGallery() { return $this->gallery; }
    public function setThumbnailUrl($thumbnail) { 
        $this->thumbnail_url = $thumbnail_url;
    }
    public function getThumbnailUrl() { return $this->thumbnail; }
    public function setUrl($url) { $this->url = $url; }
    public function getUrl() { return $this->url; }
}
