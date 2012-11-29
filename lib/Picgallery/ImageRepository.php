<?php

namespace Picgallery;

class ImageRepository implements ImageRepositoryInterface
{
    private $fileStore;
    private $thumbnailStore;
    private $_thumbnailMaker;
    
    public function __construct(
        FileStoreInterface $fileStore,
        FileStoreInterface $thumbnailStore)
    {
        $this->fileStore = $fileStore;
        $this->thumbnailStore = $thumbnailStore;
    }

    public function imageExists($name)
    {
        return $this->fileStore->fileExists($name);
    }
    
    public function uploadImage($name, $mime, $source)
    {
        $this->fileStore->uploadFile($name, $source);    
        $thumbnailMaker = $this->_getThumbnailMaker();
        $thumbnailPath = $thumbnailMaker->createThumbnail($name, $source);
        $this->thumbnailStore->uploadFile($name, $thumbnailPath);
        return $this->_convertToImage($name);
    }
    
    public function removeImage($name)
    {
        return $this->fileStore->removeFile($name)
            && $this->thumbnailStore->removeFile($name);
    }
    
    public function getImages()
    {
        $files = $this->fileStore->listFiles();
        $images = array();
        $helper = new FileHelper();
        foreach ($files as $filename) {
            if ($helper->isImageFileType($filename)) {
                $images[] = $this->_convertToImage($filename);
            }
        }
        return $images;
    }
    
    public function setThumbnailMaker(ThumbnailMakerInterface $thumbnailMaker)
    {
        $this->_thumbnailMaker = $thumbnailMaker;
    }

    private function _getThumbnailMaker()
    {
        if ($this->_thumbnailMaker == null) {
            $this->_thumbnailMaker = new ThumbnailMaker();
        }
        return $this->_thumbnailMaker;
    }

    private function _convertToImage($name)
    {
        $image = new Image();
        $image->setName($name);
        $image->setUrl($this->fileStore->getUrl($name));
        $image->setThumbnailUrl($this->thumbnailStore->getUrl($name));
        $image->setSize($this->fileStore->getFileSize($name));
        return $image;
    }
}
