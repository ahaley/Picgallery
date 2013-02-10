<?php

namespace Picgallery;

use Aws\S3\S3Client;
use Aws\S3\Enum\CannedAcl;
use Aws\Common\Enum\Region;

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
    
    public static function createS3Backed($bucketName, $gallery)
    {
        $urlBase = 'https://s3.amazonaws.com/';
        $urlPath = $urlBase . $bucketName . '/' . $gallery;
        $thumbPath = $gallery . '/thumbnails';
        $urlThumbPath = $urlBase . $bucketName . $thumbPath;
        
        /*
        $client = S3Client::factory(array(
            'key' => AWS_KEY,
            'secret' => AWS_SECRET,
            'region' => Region::US_EAST_1
        ));

        $client->createBucket(array(
            'Bucket' => $bucketName
        ));
        */
    
        $fileStore = new S3FileStore($bucketName, $gallery, $urlPath);
    //    $fileStore->setClient($client);
        $thumbStore = new S3FileStore($bucketName, $thumbPath, $urlThumbPath);
   //     $thumbStore->setClient($client);
        
        $imageStore = new ImageStore($fileStore, $thumbStore);
        return $imageStore;
    }
}
