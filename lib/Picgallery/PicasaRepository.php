<?php

namespace Picgallery;

require_once 'Zend/Loader.php';

\Zend_Loader::loadClass('Zend_Gdata');
\Zend_Loader::loadClass('Zend_Gdata_AuthSub');
\Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
\Zend_Loader::loadClass('Zend_Gdata_Photos');
\Zend_Loader::loadClass('Zend_Gdata_Photos_PhotoQuery');

require_once 'ImageRepository.php';
require_once 'PicasaAlbumRepository.php';

class PicasaRepository implements ImageRepository
{
	private $albumName = 'Picgallery';
	private $_service;
	private $_googleUser;
    private $albumRepository;
	
	public static function create($googleUser, $googleSession)
	{
		$googleToken = $googleSession->getGoogleToken();

		if (null === $googleToken)
			return null;
		
		$client = \Zend_Gdata_AuthSub::getHttpClient($googleToken);
		$service = new \Zend_Gdata_Photos($client);
		$repository = new PicasaRepository($service, $googleUser);
		if (!$repository->albumExists())
			$repository->createAlbum();
		return $repository;
	}

    public function getAlbumRepository()
    {
        return $this->albumRepository;
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
        $this->albumRepository = new PicasaAlbumRepository($service, $googleUser);
	}

	private function albumExists()
	{
        return $this->albumRepository->repositoryAlbumExists();
    }

	private function createAlbum()
	{
        $this->albumRepository->createRepositoryAlbum();
	}

	public function imageExists($image)
	{
        $feed = $this->albumRepository->getRepositoryAlbumFeed();
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

		$albumEntry = $this->albumRepository->getRepositoryAlbumEntry();

		$result = $service->insertPhotoEntry($entry, $albumEntry);

		return $result;
	}

	public function removeImage($id)
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

	private function getAlbums()
	{
        return $this->albumRepository->getAlbums();
	}

    public function getImages()
    {
        $images = array();
        $feed = $this->albumRepository->getRepositoryAlbumFeed();

        foreach ($feed as $entry) {
            if ($entry instanceof \Zend_Gdata_Photos_PhotoEntry) {
                $title = $entry->getTitle();
                $thumb = $entry->getMediaGroup()->getThumbnail();
                $images[] = (object)array(
                    'title' => $title,
                    'thumbnail' => $thumb[1]->getUrl()
                );
            }
        }

        return $images;
    }
}
