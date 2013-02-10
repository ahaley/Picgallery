<?php

namespace Picgallery;

use Aws\S3\S3Client;
use Aws\S3\Enum\CannedAcl;
use Aws\Common\Enum\Region;

class S3FileStore implements FileStoreInterface
{
    private $client;
    private $bucketName;
    private $localPath;
    private $urlPath;

    public function __construct($bucketName = 'picgallery_bucket', $localPath, $urlPath)
    {
        $this->bucketName = $bucketName;
        $this->localPath = $localPath;
        $this->urlPath = $urlPath;
    }

    public function fileExists($filename)
    {
    }
    
    public function uploadFile($filename, $path)
    {
        $client = $this->getClient();
        $fp = fopen($path, "r");
    
        $key = $this->localPath . '/' . $filename;
    
        $model = $client->putObject(array(
            'Bucket' => $this->bucketName,
            'Key' => $key,
            'Body' => $fp,
            'ACL' => CannedAcl::PUBLIC_READ,
        ));
        
        fclose($fp);
    }
    
    public function getUrl($filename)
    {
        return $this->urlPath . '/' . $filename;
    }
    
    public function getFileSize($filename)
    {
        return 0;
    }
    
    public function removeFile($filename)
    {
        $client = $this->getClient();
        $response = $client->deleteObject(array(
            'Bucket' => $this->bucketName,
            'Key' => $this->localPath . '/' . $filename
        ));
    }
    
    public function listFiles()
    {
    }
    
    public function setClient($client)
    {
        $this->client = $client;
    }
    
    private function getClient()
    {
        if ($this->client === NULL) {
            $this->client = S3Client::factory(array(
                'key' => AWS_KEY,
                'secret' => AWS_SECRET,
                'region' => Region::US_EAST_1
            ));
            
            $this->client->createBucket(array(
                'Bucket' => $this->bucketName
            ));
        }

        return $this->client;
    }
}
