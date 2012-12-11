<?php

namespace Picgallery;

class ImageRetrieval implements ImageRetrievalInterface
{
    private $conn;
    private $gallery;

    public function __construct(\Doctrine\DBAL\Connection $conn,
        $gallery = 'default')
    {
        $this->conn = $conn;
        $this->gallery = $gallery;
    }

    public function imageExists($name)
    {
        return false;
    }

    public function disable($name)
    {
        return false;
    }

    public function record(Image $image)
    {
        $this->conn->insert('picgallery_image', array(
            'name' => $image->getName(),
            'gallery' => $image->getGallery(),
            'url' => $image->getUrl(),
            'thumbnail_url' => $image->getThumbnailUrl()
        ));
    }

    public function getImage($name)
    {
        $sql = <<<EOD
            SELECT gallery, name, url, thumbnail_url FROM picgallery_image
            WHERE gallery = :gallery AND name = :name
EOD;
        $result = $this->conn->fetchAssoc($sql, array(
            'gallery' => $this->gallery,
            'name' => $name
        ));
        if (!$result)
            return null;
        return Image::populate($result);
    }

    public function getImages()
    {
        $sql = <<<EOD
            SELECT gallery, name, url, thumbnail_url FROM picgallery_image
            WHERE gallery = :gallery;
EOD;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('gallery', $this->gallery);
        $stmt->execute();
        $images = array();
        while ($row = $stmt->fetch()) {
            $images[] = Image::populate($row);
        }
        return $images;
    }
}
