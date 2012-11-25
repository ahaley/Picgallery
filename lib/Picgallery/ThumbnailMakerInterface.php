<?php

namespace Picgallery;

interface ThumbnailMakerInterface
{
    public function createThumbnail($name, $source);
}
