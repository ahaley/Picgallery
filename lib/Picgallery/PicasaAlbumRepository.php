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
    private $googleUser;
    private $albumName = 'Picgallery';

    public function __construct($service, $googleUser)
    {
        $this->service = $service;
        $this->googleUser = $googleUser;
    }

    public function repositoryAlbumExists() 
    {
		$entries = $this->service->getUserFeed("default");
		foreach ($entries as $entry) {
			if ($entry instanceof Zend_Gdata_Photos_AlbumEntry) {
				if ($entry->getTitle() == $this->albumName)
					return true;
			}
		}
		return false;
    }

    public function createRepositoryAlbum()
    {
		$service = $this->service;
		$entry = new \Zend_Gdata_Photos_AlbumEntry();
		$entry->setTitle($service->newTitle($this->_album));
		$entry->setSummary($service->newSummary('Picgallery album'));
		$newEntry = $service->insertAlbumEntry($entry);
    }

    public function getAlbums()
    {
		$query = new \Zend_GData_Photos_UserQuery();
		$query->setUser($this->googleUser);

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

    public function getRepositoryAlbumEntry()
    {		
        $query = $this->createAlbumQuery();
		$entry = $service->getAlbumEntry($query);
        return $entru;
    }

    private function createAlbumQuery()
    {
        $query = new \Zend_Gdata_Photos_AlbumQuery();
		$query->setUser($this->googleUser);
		$query->setAlbumName($this->albumName);
        return $query;
    }

}
