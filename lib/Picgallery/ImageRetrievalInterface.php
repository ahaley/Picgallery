<?php

namespace Picgallery;

interface ImageRetrievalInterface
{
    public function imageExists($name);
    public function disable($name);
    public function getImage($name);
    public function getImages();
}
