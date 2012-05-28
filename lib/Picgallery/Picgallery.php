<?php

namespace Picgallery; 

require_once 'GoogleSession.php';
require_once 'PicasaAdapter.php';
require_once 'DropboxAdapter.php';
require_once 'PictureSyncer.php';

class Picgallery
{
	const GoogleError = 0;

	private $_picasaAdapter;

	private $_dropboxAdapter;

	private $_pictureSyncer;

	public static function create($dropboxKey, $dropboxSecret, $googleUser, $googleSession, $nextUrl)
	{
		$picasaAdapter = PicasaAdapter::create($googleUser, $googleSession);
		if ($picasaAdapter === null) {
			$result = new \stdClass;
			$result->error = Picgallery::GoogleError;
			$result->authUrl = PicasaAdapter::getAuthUrl($nextUrl);
			return $result;
		}

		$dropboxAdapter = DropboxAdapter::create(
			$dropboxKey,
			$dropboxSecret,
			$nextUrl
		);
		
		$pictureSyncer = new PictureSyncer($picasaAdapter);
		
		return new Picgallery($picasaAdapter, $dropboxAdapter, $pictureSyncer);
	}

	public function __construct($picasaAdapter, $dropboxAdapter, $pictureSyncer)
	{
		$this->_picasaAdapter = $picasaAdapter;
		$this->_dropboxAdapter = $dropboxAdapter;
		$this->_pictureSyncer = $pictureSyncer;
	}

	public function getImageList()
	{
		$imageList = $this->_dropboxAdapter->getImageList();
		return $this->_pictureSyncer->mapImageExistence($imageList);
	}

}
