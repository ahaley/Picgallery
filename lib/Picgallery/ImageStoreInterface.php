<?php

namespace Picgallery;

interface ImageStoreInterface
{
    public function upload($name, $type, $path);
    public function remove($name);
}
