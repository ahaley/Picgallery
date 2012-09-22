<?php
namespace Picgallery;

class Image
{
	private $id;
    private $name;
    private $thumbnail;
    private $url;
    
	public function setId($id) { $this->id = $id; }
	public function getId() { return $this->id; }
    public function setName($name) { $this->name = $name; }
    public function getName() { return $this->name; }
    public function setThumbnailUrl($thumbnail) { $this->thumbnail = $thumbnail; }
    public function getThumbnailUrl() { return $this->thumbnail; }
    public function setUrl($url) { $this->url = $url; }
    public function getUrl() { return $this->url; }
}
