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
require_once 'Image.php';

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

	public function __construct($service = null, $username = null, $albumRepository = null)
	{
		$this->service = $service;
		$this->username = $username;
        $this->albumRepository = $albumRepository != null ? $albumRepository 
        	: new AlbumRepository(new PicasaAlbumAdapter($service, $username));
	}

	public function imageExists($path)
	{
        $feed = $this->albumRepository->getRepositoryImages();
		foreach ($feed as $entry) {
			if ($entry instanceof \Zend_Gdata_Photos_PhotoEntry) {
				if (strpos($path, $entry->getTitleValue()) !== false) {
					return $entry->getGphotoId();
				}
			}
		}
		return false;
	}

	public function uploadImage($title, $mime, $tmp_path)
	{
		$service = $this->service;

		$fd = $service->newMediaFileSource($tmp_path);
		$fd->setContentType($mime);

		$entry = new \Zend_Gdata_Photos_PhotoEntry();
		$entry->setMediaSource($fd);
		$entry->setTitle($service->newTitle($title));

		$albumEntry = $this->albumRepository->getRepositoryAlbum();

		$result = $service->insertPhotoEntry($entry, $albumEntry);

		return $result;
	}

	public function removeImage($id)
	{
        $query = $this->albumRepository->createPhotoQuery();
		$query->setPhotoId($id);

		$photoEntry = $this->service->getPhotoEntry($query);

		$this->service->deletePhotoEntry($photoEntry, true);
	}

    public function getImage($id)
    {
        $query = $this->albumRepository->createPhotoQuery();
        $query->setPhotoId($id);

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

    private function _getImageFromPhotoEntry(\Zend_Gdata_Photos_PhotoEntry $entry)
    {
        $title = $entry->getTitleValue();
        $mediaGroup = $entry->getMediaGroup();
        $thumb = $mediaGroup->getThumbnail();
        $content = $mediaGroup->getContent();
        $image = new Image();
        $image->setId($entry->getGphotoId());
        $image->setName($title);
        $image->setThumbnailUrl($thumb[1]->getUrl());
        $image->setUrl($content[0]->getUrl());
        return $image;
    }
}
