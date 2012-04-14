<?php

namespace Picgallery; 

require_once 'GoogleSession.php';
require_once 'PicasaAdapter.php';
require_once 'DropboxAdapter.php';
require_once 'PictureSync.php';

class Picgallery
{
	const GoogleError = 0;

	private $_picasaAdapter;

	private $_dropboxAdapter;

	private $_imageSync;

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
		
		$pictureSync = new PictureSync($picasaAdapter);
		
		return new Picgallery($picasaAdapter, $dropboxAdapter, $pictureSync);
	}

	public function __construct($picasaAdapter, $dropboxAdapter, $imageSync)
	{
		$this->_picasaAdapter = $picasaAdapter;
		$this->_dropboxAdapter = $dropboxAdapter;
		$this->_imageSync = $imageSync;
	}

	public function getPictureList()
	{
		$imageList = $this->_dropboxAdapter->getImageList();
		return $this->_imageSync->updateImageList($imageList);
	}

}
