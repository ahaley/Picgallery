<?php

namespace Picgallery;

class PicasaAlbumAdapter implements AlbumAdapter 
{
    private $service;
    private $username;

    public function __construct($service, $username)
    {
        $this->service = $service;
        $this->username = $username;
    }

    public function hasAlbum($albumName)
    {
        $query = $this->createAlbumQuery($albumName);
        try {
            $album = $this->service->getAlbumEntry($query);
        }
        catch (\Zend_Gdata_App_HttpException $ex) {
            return false;
        }
        return true;
    }

    public function getAlbum($albumName)
    {
        $query = $this->createAlbumQuery($albumName);
        return $this->service->getAlbumEntry($query);
    }

    public function createAlbum($albumName, $summary = null)
    {
        $service = $this->service;
		$entry = new \Zend_Gdata_Photos_AlbumEntry();
		$entry->setTitle($service->newTitle($albumName));
        if ($summary != null) {
		    $entry->setSummary($service->newSummary($summary));
        }
		$newEntry = $service->insertAlbumEntry($entry);
    }

    public function getAlbums()
    {
		$query = new \Zend_GData_Photos_UserQuery();
		$query->setUser($this->username);

        $userFeed = $this->service->getUserFeed(null, $query);

		$albums = array();
		foreach ($userFeed as $entry) {
			if ($entry instanceof \Zend_Gdata_Photos_AlbumEntry) {
				$albums[$entry->getTitle()->getText()] = $entry;
			}
		}
		return $albums;
    }

    public function getAlbumFeed($albumName)
    {
        $query = $this->createAlbumQuery($albumName);
        return $this->service->getAlbumFeed($query);
    }

    private function createAlbumQuery($albumName)
    {
        $query = new \Zend_Gdata_Photos_AlbumQuery();
		$query->setUser($this->username);
		$query->setAlbumName($albumName);
//        $query->setImgMax("d");
        return $query;
    }
}
