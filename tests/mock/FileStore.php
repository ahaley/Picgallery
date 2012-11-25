<?php

namespace tests\mock;

class FileStore implements \Picgallery\FileStoreInterface
{
    private $localPath;
    private $urlPath;
    private $files;

    public function __construct($localPath, $urlPath)
    {
        $this->localPath = $localPath;
        $this->urlPath = $urlPath;
    }

    public function fileExists($filename)
    {
        return isset($this->files[$filename]);
    }
    
    public function uploadFile($filename, $source)
    {
        $this->files[$filename] = $source;
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
        unset($this->files[$filename]);
    }

    public function listFiles()
    {
        return array_keys($this->files);
    }

    private function _filePath($filename)
    {
        return $this->localPath . DIRECTORY_SEPARATOR . $filename;
    }
}
