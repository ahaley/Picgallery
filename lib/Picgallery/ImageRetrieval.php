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

    public function getImage($gallery, $name)
    {
        $sql = <<<EOD
            SELECT gallery, name, url, thumbnail_url FROM picgallery_image
            WHERE gallery = :gallery AND name = :name
EOD;
        $result = $this->conn->fetchAssoc($sql, array(
            'gallery' => $gallery,
            'name' => $name
        ));
        return Image::populate($result);
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
