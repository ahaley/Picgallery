<?php

namespace Picgallery;

$include_path = explode(':', get_include_path());
$include_path[] = realpath(__DIR__ . '/../../vendor/Dropbox');
set_include_path(implode(':', $include_path));

class DropboxAdapter
{
    private $_dropbox;

    public static function create($key, $secret, $nextUrl)
    {
        $storage = new \Dropbox\OAuth\Storage\Session;
        $oauth = new \Dropbox\OAuth\Consumer\Curl(
            $key, $secret, $storage, $nextUrl
        );
        $dropbox = new \Dropbox\API($oauth);
        return new DropboxAdapter($dropbox);
    }

    public function __construct($dropbox = null)
    {
        $this->_dropbox = $dropbox;
    }

    public function getMetadata()
    {       
        $response = $this->_dropbox->metaData();
        return $response['body'];
    }

    public function getImageList()
    {
        $metadata = $this->getMetadata();
        $images = array();
        $fileHelper = new FileHelper;
        
        foreach ($metadata->contents as $content) {
            $mime_type = $content->mime_type;
            
            if ($fileHelper->isImageType($mime_type)) {
            
                $images[] = (object)array(
                    'file' => $content->path,
                    'size' => $content->size,
                    'mime_type' => $content->mime_type
                );
                
            }
        }
        return $images;
    }
    
    public function downloadFile($file)
    {
        $result = $this->_dropbox->getFile($file);
        return $result;
    }
}
