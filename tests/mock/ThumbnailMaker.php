<?php

namespace tests\mock;

class ThumbnailMaker implements \Picgallery\ThumbnailMakerInterface
{
    public function createThumbnail($name, $source)
    {
        return "thumbnail" . DIRECTORY_SEPARATOR . $name;
    }
}
