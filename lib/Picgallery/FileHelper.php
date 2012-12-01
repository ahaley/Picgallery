<?php

namespace Picgallery;

class FileHelper
{
    public function isImageType($mime_type)
    {
        return false !== strpos($mime_type, 'image');
    }
    
    public function isImageFileType($filename)
    {
        $extension = strtolower($this->getFileExtension($filename));
        
        return in_array($extension, array("png", "gif", "jpg"));
    }

    public function getFileExtension($path)
    {
        preg_match('/\.(\w+)$/', strtolower($path), $matches);
        if (count($matches) < 2)
            return null;
        return $matches[1];
    }
}
