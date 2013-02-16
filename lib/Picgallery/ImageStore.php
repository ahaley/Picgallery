<?php

namespace Picgallery;

class ImageStore implements ImageStoreInterface
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

    public function upload($name, $type, $path)
    {
        $this->fileStore->uploadFile($name, $path);    
        $thumbnailMaker = $this->_getThumbnailMaker();
        $thumbnailPath = $thumbnailMaker->createThumbnail($name, $path);
        $this->thumbnailStore->uploadFile($name, $thumbnailPath);
        return $this->_convertToImage($name);
    }

    public function remove($name)
    {
        $this->fileStore->removeFile($name);  
        $this->thumbnailStore->removeFile($name);
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
//        $image->setSize($this->fileStore->getFileSize($name));
        return $image;
    }
}
