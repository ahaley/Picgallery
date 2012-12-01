<?php

namespace Picgallery;

class Image
{
    private $name;
    private $gallery;
    private $url;
    private $thumbnail_url;
    private $size;

    public static function populate($values)
    {
        $image = new self;
        $image->name = $values['name'];
        $image->gallery = $values['gallery'];
        $image->url = $values['url'];
        $image->thumbnail_url = $values['thumbnail_url'];
        return $image;
    }
    
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
