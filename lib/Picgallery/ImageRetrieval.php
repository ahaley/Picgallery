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
        $sql = "SELECT gallery, name, url, thumbnail_url FROM picgallery_image";
        $stmt = $this->conn->query($sql);
        $images = array();
        while ($row = $stmt->fetch()) {
            $images[] = Image::populate($row);
        }
        return $images;
    }
}
