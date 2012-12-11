<?php

namespace Picgallery;

class ImageStoreFactory
{
    public static function createFileBacked($basePath, $baseUrl, $gallery)
    {
        $localPath = $basePath . '/' . $gallery;
        $thumbPath = $localPath . '/thumbnails';
        $urlPath = $baseUrl . '/' . $gallery;
        $urlThumb = $urlPath . '/thumbnails';
        $fileStore = new FileStore($localPath, $urlPath);
        $thumbStore = new FileStore($thumbPath, $urlThumb);
        $imageStore = new ImageStore($fileStore, $thumbStore);
 
        return $imageStore;
    }
}
