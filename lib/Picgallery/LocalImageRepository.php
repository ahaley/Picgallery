<?php
namespace Picgallery;

require_once 'ImageRepository.php';

class LocalImageRepository implements ImageRepository
{
    private $fileStore;
    private $thumbnailStore;
    
    public function __construct(
        \Picgallery\FileStoreInterface $fileStore,
        \Picgallery\FileStoreInterface $thumbnailStore)
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
    
    private function _getThumbnailMaker()
    {
        return new ThumbnailMaker();
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
