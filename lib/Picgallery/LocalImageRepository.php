<?php
namespace Picgallery;

require_once 'ImageRepository.php';

class LocalImageRepository implements ImageRepository
{
	private $local_path;
	private $url_path;
	
    public function __construct($local_path, $url_path)
    {
    	$this->local_path = $local_path;
    	if (!file_exists($local_path))
    		mkdir($local_path, 0777, true);
    	$this->url_path = $url_path;
    }

	public function imageExists($image)
	{
		return file_exists($this->local_path . '/' . $image);
	}
	
	public function uploadImage($title, $mime, $tmp_path)
	{
		$destination = $this->local_path . '/' . $title;
		$result = move_uploaded_file($tmp_path, $destination);
		
		if (!$result) {
			rename($tmp_path, $destination);
		}
		
		$image = new \Imagick($destination);
		$imageprops = $image->getImageGeometry();
    	if ($imageprops['width'] <= 200 && $imageprops['height'] <= 200) {
    	} else {
        	$image->resizeImage(200,200, \Imagick::FILTER_LANCZOS, 0.9, true);
    	}
    	
    	$thumb_path = $this->local_path . '/thumbnails/';
    	
    	if (!file_exists($thumb_path))
    		mkdir($thumb_path);
    	
    	$image->writeImage($thumb_path . '/' . $title);
    	
    	return $this->_convertToImage($title, $this->local_path);
	}
	
	public function removeImage($id)
	{
		$file = $this->local_path . '/' . $id;
		if (file_exists($file)) {
			return unlink($file);
		}
		return false;
	}
    
    public function getImages()
    {
    	$images = array();
    	$helper = new FileHelper();
    	if ($handle = opendir($this->local_path)) {
    		while (false !== ($entry = readdir($handle))) {
    			if (!$helper->isImageFileType($entry))
    				continue;
    				
    			$images[] = $this->_convertToImage($entry, $this->local_path);
    		}
    		closedir($handle);
    	}
    	return $images;
    }
    
    private function _convertToImage($filename, $path)
    {
    	$image = new Image();
    	$image->setName($filename);
    	$image->setUrl($this->url_path . '/' . $filename);
    	$image->setThumbnailUrl($this->url_path . '/thumbnails/' . $filename);
    	$image->setSize(filesize($path . '/' . $filename));
    	return $image;
    }
}
