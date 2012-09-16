<?php

namespace Picgallery;

require_once 'Zend/Loader.php';

\Zend_Loader::loadClass('Zend_Gdata_Photos_UserQuery');
\Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumQuery');
\Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumEntry');

require_once 'ImageAlbumRepository.php';

class PicasaAlbumRepository implements ImageAlbumRepository 
{
    private $service;
    private $username;
    private $albumName = 'Picgallery';
    private $albumEntry = null;

    public function __construct($service, $username)
    {
        $this->service = $service;
        $this->username = $username;
    }

    public function repositoryAlbumExists() 
    {
        $albumEntry = $this->getRepositoryAlbumEntry();
        return $albumEntry != null;
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

    public function getRepositoryAlbumFeed()
    {
        $query = $this->createAlbumQuery();
        $feed = $this->service->getAlbumFeed($query);
        return $feed;
    }

    private function createAlbumQuery()
    {
        $query = new \Zend_Gdata_Photos_AlbumQuery();
		$query->setUser($this->username);
		$query->setAlbumName($this->albumName);
        return $query;
    }

    private function createRepositoryAlbum()
    {
		$service = $this->service;
		$entry = new \Zend_Gdata_Photos_AlbumEntry();
		$entry->setTitle($service->newTitle($this->albumName));
		$entry->setSummary($service->newSummary('Picgallery album'));
		$newEntry = $service->insertAlbumEntry($entry);
    }

    private function _getRepositoryAlbumEntry($query)
    {
        $i = 0;
        $entry = null;
        $max_attempts = 3;
        while ($entry == null && $i < $max_attempts) {
            try {
                $entry = $this->service->getAlbumEntry($query);
            }
            catch (\Zend_Gdata_App_HttpException $ex) {
                $entry = null;
                $this->createRepositoryAlbum();
            }
            $i++;
        }
        return $entry;
    }

    public function getRepositoryAlbumEntry()
    {
        if ($this->albumEntry == null) {
            $query = $this->createAlbumQuery();
            $this->albumEntry = $this->_getRepositoryAlbumEntry($query);
        }
        return $this->albumEntry;
    }
}
