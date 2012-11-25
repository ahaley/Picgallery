<?php

namespace Picgallery;

interface FileStoreInterface
{
    public function fileExists($filename);
    public function uploadFile($filename, $source);
    public function getUrl($filename);
    public function getFileSize($filename);
    public function removeFile($filename);
    public function listFiles();
}

