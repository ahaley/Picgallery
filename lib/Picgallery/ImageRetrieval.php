<?php

namespace Picgallery;

class ImageRetrieval implements ImageRetrievalInterface
{
    private $conn;

    public function __construct(\Doctrine\DBAL\Connection $conn)
    {
        $this->conn = $conn;
    }

    public function imageExists($name)
    {
        return false;
    }

    public function disable($name)
    {
        return false;
    }

    public function getImages()
    {
    }
}
