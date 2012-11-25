<?php

namespace tests\mock;

class FileStore implements \Picgallery\FileStoreInterface
{
    private $localPath;
    private $urlPath;

    public function __construct($localPath, $urlPath)
    {
        $this->localPath = $localPath;
        $this->urlPath = $urlPath;
        if (!file_exists($localPath)) {
            mkdir($localPath, 0777, true);
        }
    }

    public function fileExists($filename)
    {
        return file_exists($this->_filePath($filename));
    }
    
    public function uploadFile($filename, $source)
    {
        $destination = $this->_filePath($filename);
        if (!move_uploaded_file($source, $destination)) {
            copy($source, $destination);
        }
    }

    public function getUrl($filename)
    {
        return $this->urlPath . '/' . $filename;
    }

    public function getFileSize($filename)
    {
        return filesize($this->_filePath($filename));
    }

    public function removeFile($filename)
    {
        $path = $this->_filePath($filename);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    public function listFiles()
    {
        $filenames = array();
        if ($handle = opendir($this->localPath)) {
            while (false !== ($entry = readdir($handle))) {
                $filenames[] = $entry;
            }
        }
        closedir($handle);
        return $filenames;
    }

    private function _filePath($filename)
    {
        return $this->localPath . DIRECTORY_SEPARATOR . $filename;
    }
}
