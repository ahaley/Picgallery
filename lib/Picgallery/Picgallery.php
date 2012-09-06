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

	public static function create($dropboxKey, $dropboxSecret, $googleUser, $googleSession, $nextUrl)
	{
		$imageRepository = PicasaRepository::create($googleUser, $googleSession);
		if ($imageRepository === null) {
			$result = new \stdClass;
			$result->error = Picgallery::GoogleError;
			$result->authUrl = PicasaRepository::getAuthUrl($nextUrl);
			return $result;
		}

		$dropboxAdapter = DropboxAdapter::create(
			$dropboxKey,
			$dropboxSecret,
			$nextUrl
		);
		
		$imageSyncer = new ImageSyncer($imageRepository);
		
		return new Picgallery($imageRepository, $dropboxAdapter, $imageSyncer);
	}

	public function __construct($imageRepository, $dropboxAdapter, $imageSyncer)
	{
		$this->_imageRepository = $imageRepository;
		$this->_dropboxAdapter = $dropboxAdapter;
		$this->_imageSyncer = $imageSyncer;
	}

	public function getImageList()
	{
		$imageList = $this->_dropboxAdapter->getImageList();
		return $this->_imageSyncer->mapImageExistence($imageList);
	}

}
