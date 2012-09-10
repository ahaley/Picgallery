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
	private $service;
	private $username;
    private $albumRepository;
	
	public static function create($googleSession)
	{
        $client = $googleSession->getHttpClient();

		if (null === $client)
			return null;
		
		$service = new \Zend_Gdata_Photos($client);
		$repository = new PicasaRepository(
            $service,
            $googleSession->getUsername()
        );
		return $repository;
	}

	public function __construct($service = null, $username = null)
	{
		$this->service = $service;
		$this->username = $username;
        $this->albumRepository = new PicasaAlbumRepository($service, $username);
	}

    public function getAlbumRepository()
    {
        return $this->albumRepository;
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
		$service = $this->service;

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
		$service = $this->service;

		$photoQuery = new \Zend_Gdata_Photos_PhotoQuery;
		$photoQuery->setUser($this->username);
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
