<?php

namespace tests/mock;

class ThumbnailMaker extends \Picgallery\ThumbnailMakerInterface
{
    public function createThumbnail($name, $source)
    {
        return false;
    }
}
