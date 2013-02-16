<?php

namespace Picgallery;

class ThumbnailMaker implements ThumbnailMakerInterface
{
    public function createThumbnail($name, $source)
    {
        $image = new \Imagick($source);
        $imageprops = $image->getImageGeometry();
        if ($imageprops['width'] <= 200 && $imageprops['height'] <= 200) {
        } else {
            $image->resizeImage(200, 200, \Imagick::FILTER_LANCZOS, 0.9, true);
        }

        $destination = tempnam(sys_get_temp_dir(), $name);
        $image->writeImage($destination);
        return $destination;
    }
}
