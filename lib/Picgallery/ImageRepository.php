<?php

namespace Picgallery;

class ImageRepository implements ImageRepositoryInterface
{
    private $store;
    private $retrieval;
    
    public function __construct(
        ImageStoreInterface $store,
        ImageRetrievalInterface $retrieval)
    {
        $this->store = $store;
        $this->retrieval = $retrieval;
    }

    public function imageExists($name)
    {
        return $this->retrieval->imageExists($name);
    }
    
    public function uploadImage($name, $type, $path)
    {
        return $this->store->upload($name, $type, $path);
    }
    
    public function removeImage($name)
    {
        return $this->retrieval->disable($name);
    }
    
    public function getImages()
    {
        return $this->retrieval->getImages();
    }
}
