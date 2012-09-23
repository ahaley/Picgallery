<?php

namespace Picgallery; 

require_once 'GoogleSession.php';
require_once 'PicasaRepository.php';
require_once 'DropboxAdapter.php';
require_once 'ImageSyncer.php';

class Picgallery
{
	const GoogleError = 0;

	private $_imageRepository;

	private $_dropboxAdapter;

	private $_imageSyncer;

	public static function createWithImageRepository(
		ImageRepository $repository, $dropboxKey, $dropboxSecret, $nextUrl)
	{
		$dropboxAdapter = DropboxAdapter::create(
			$dropboxKey,
			$dropboxSecret,
			$nextUrl
		);
		return new Picgallery($repository, $dropboxAdapter);
	}

	public static function create(
        $dropboxKey, $dropboxSecret, $googleSession, $nextUrl)
	{
		$imageRepository = PicasaRepository::create($googleSession);
		if ($imageRepository === null) {
			$result = new \stdClass;
			$result->error = Picgallery::GoogleError;
			$result->authUrl = $googleSession->getAuthUrl($nextUrl);
			return $result;
		}

		$dropboxAdapter = DropboxAdapter::create(
			$dropboxKey,
			$dropboxSecret,
			$nextUrl
		);
		
		return new Picgallery($imageRepository, $dropboxAdapter);
	}

	public function __construct($imageRepository, $dropboxAdapter)
	{
		$this->_imageRepository = $imageRepository;
		$this->_dropboxAdapter = $dropboxAdapter;
		$this->_imageSyncer = new ImageSyncer($imageRepository);
	}

	public function getImageList()
	{
		$imageList = $this->_dropboxAdapter->getImageList();
		return $this->_imageSyncer->mapImageExistence($imageList);
	}

}
