<?php

namespace Picgallery;

require_once 'Zend/Loader.php';

\Zend_Loader::loadClass('Zend_Gdata');
\Zend_Loader::loadClass('Zend_Gdata_AuthSub');
\Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
\Zend_Loader::loadClass('Zend_Gdata_Photos');
\Zend_Loader::loadClass('Zend_Gdata_Photos_PhotoQuery');

require_once 'ImageRepository.php';
require_once 'PicasaAlbumAdapter.php';
require_once 'AlbumRepository.php';

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
        $this->albumRepository = new AlbumRepository(
            new PicasaAlbumAdapter($service, $username)
        );
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
        $feed = $this->albumRepository->getRepositoryImages();
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

		$albumEntry = $this->albumRepository->getRepositoryAlbum();

		$result = $service->insertPhotoEntry($entry, $albumEntry);

		return $result;
	}

	public function removeImage($photoId)
	{
        $query = $this->albumRepository->createPhotoQuery();
		$query->setPhotoId($photoId);

		$photoEntry = $this->service->getPhotoEntry($query);

		$this->service->deletePhotoEntry($photoEntry, true);
	}

    public function getImage($photoId)
    {
        $query = $this->albumRepository->createPhotoQuery();
        $query->setPhotoId($photoId);

        $entry = $this->service->getPhotoEntry($query);
        return $this->_getImageFromPhotoEntry($entry);
    }

    public function getImages()
    {
        $images = array();
        $feed = $this->albumRepository->getRepositoryImages();

        foreach ($feed as $entry) {
            if ($entry instanceof \Zend_Gdata_Photos_PhotoEntry) {
                $images[] = $this->_getImageFromPhotoEntry($entry);
            }
        }

        return $images;
    }

    private function _getImageFromPhotoEntry(
        \Zend_Gdata_Photos_PhotoEntry $entry)
    {
        $title = $entry->getTitleValue();
        $mediaGroup = $entry->getMediaGroup();
        $thumb = $mediaGroup->getThumbnail();
        $content = $mediaGroup->getContent();
        return (object)array(
            'id' => $entry->getGphotoId(),
            'title' => $title,
            'thumbnail' => $thumb[1]->getUrl(),
            'image_url' => $content[0]->getUrl()
        );
    }
}
