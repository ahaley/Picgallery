<?php

namespace Picgallery;

require_once 'Zend/Loader.php';

\Zend_Loader::loadClass('Zend_Gdata');
\Zend_Loader::loadClass('Zend_Gdata_AuthSub');
\Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
\Zend_Loader::loadClass('Zend_Gdata_Photos');
\Zend_Loader::loadClass('Zend_Gdata_Photos_UserQuery');
\Zend_Loader::loadClass('Zend_Gdata_Photos_PhotoQuery');
\Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumQuery');
\Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumEntry');

require_once 'ImageRepository.php';

class PicasaRepository implements ImageRepository
{
	private $_album = 'Picgallery';
	private $_service;
	private $_googleUser;
	
	public static function create($googleUser, $googleSession)
	{
		$googleToken = $googleSession->getGoogleToken();

		if (null === $googleToken)
			return null;
		
		$client = \Zend_Gdata_AuthSub::getHttpClient($googleToken);
		$service = new \Zend_Gdata_Photos($client);
		$adapter = new PicasaRepository($service, $googleUser);
		if (!$adapter->albumExists())
			$adapter->createAlbum();
		return $adapter;
	}

	public static function getAuthUrl($nextUrl)
	{
		$scope = 'http://picasaweb.google.com/data';
		$secure = false;
		$session = true;
		return \Zend_Gdata_AuthSub::getAuthSubTokenUri($nextUrl, $scope, $secure, $session);
	}

	public function __construct($service = null, $googleUser = null)
	{
		$this->_service = $service;
		$this->_googleUser = $googleUser;
	}

	public function albumExists()
	{
		$service = $this->_service;
		$userFeed = $service->getUserFeed("default");
		foreach ($userFeed as $entry) {
			if ($entry instanceof Zend_Gdata_Photos_AlbumEntry) {
				if ($entry->getTitle() == $this->_album)
					return true;
			}
		}
		return false;
	}

	public function createAlbum()
	{
		$service = $this->_service;
		$entry = new \Zend_Gdata_Photos_AlbumEntry();
		$entry->setTitle($service->newTitle($this->_album));
		$entry->setSummary($service->newSummary('Picgallery album'));
		$newEntry = $service->insertAlbumEntry($entry);
	}

	public function imageExists($image)
	{
		$service = $this->_service;
		
		$query = new \Zend_GData_Photos_AlbumQuery();
		$query->setUser($this->_googleUser);
		$query->setAlbumName($this->_album);
		
		$feed = $service->getAlbumFeed($query);
		
		foreach ($feed as $entry) {
			if ($entry instanceof \Zend_Gdata_Photos_PhotoEntry) {
				if (strpos($image, $entry->getTitleValue()) !== false) {
					return $entry->getGphotoId();
				}
			}
		}

		return false;
	}

	public function uploadImage($title, $mime, $file_path)
	{
		$service = $this->_service;

		$fd = $service->newMediaFileSource($file_path);
		$fd->setContentType($mime);

		$entry = new \Zend_Gdata_Photos_PhotoEntry();
		$entry->setMediaSource($fd);
		$entry->setTitle($service->newTitle($title));

		$albumQuery = new \Zend_Gdata_Photos_AlbumQuery();
		$albumQuery->setUser($this->_googleUser);
		$albumQuery->setAlbumName($this->_album);

		$albumEntry = $service->getAlbumEntry($albumQuery);

		$result = $service->insertPhotoEntry($entry, $albumEntry);

		return $result;
	}

	public function removePhoto($photoId)
	{
		$service = $this->_service;

		$photoQuery = new \Zend_Gdata_Photos_PhotoQuery;
		$photoQuery->setUser($this->_googleUser);
		$photoQuery->setAlbumName($this->_album);
		$photoQuery->setPhotoId($photoId);
		$photoQuery->setType('entry');

		$entry = $service->getPhotoEntry($photoQuery);

		$service->deletePhotoEntry($entry, true);
	}

	public function getAlbums()
	{
		$service = $this->_service;
		$query = new \Zend_GData_Photos_UserQuery();

		$query->setUser($this->_googleUser);

		try {
			$userFeed = $service->getUserFeed(null, $query);
		}
		catch (\Zend_GData_App_Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		$albums = array();
		foreach ($userFeed as $entry) {
			if ($entry instanceof \Zend_Gdata_Photos_AlbumEntry) {
				$albums[$entry->getTitle()->getText()] = $entry;
			}
		}
		return $albums;
	}

    public function getPhotos()
    {
        $photos = array();
        $service = $this->_service;
        $query = new \Zend_Gdata_Photos_AlbumQuery();
        $query->setUser($this->_googleUser);
        $query->setAlbumName($this->_album);

        $feed = $service->getAlbumFeed($query);

        foreach ($feed as $entry) {
            if ($entry instanceof \Zend_Gdata_Photos_PhotoEntry) {
                $title = $entry->getTitle();
                $thumb = $entry->getMediaGroup()->getThumbnail();
                $photos[] = (object)array(
                    'title' => $title,
                    'thumbnail' => $thumb[1]->getUrl()
                );

            }
        }

        return $photos;
    }
}
