<?php

namespace Picgallery;

require_once 'Zend/Loader.php';

\Zend_Loader::loadClass('Zend_Gdata_Photos_UserQuery');
\Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumQuery');
\Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumEntry');

require_once 'AlbumAdapter.php';

class AlbumRepository 
{
    private $albumAdapter;
    private $albumName = 'Picgallery';
    private $albumSummary = 'Picgallery repository';
    private $album = null;

    public function __construct(AlbumAdapter $adapter)
    {
        $this->albumAdapter = $adapter; 
        $this->repositoryAlbumExists();
    }

    public function repositoryAlbumExists() 
    {
        return $this->albumAdapter->hasAlbum($this->albumName);
    }

    private function _getRepositoryAlbum($query)
    {
        $i = 0;
        $album = null;
        $max_attempts = 3;
        $adapter = $this->albumAdapter;
        while ($album == null && $i < $max_attempts) {
            $album = $adapter->getAlbum($this->albumName);
            if ($album == null) {
                $adapter->createAlbum($this->albumName);
            }
            $i++;
        }
        return $album;
    }

    public function getRepositoryAlbum()
    {
        if ($this->album == null) {
            $this->album = $this->_getRepositoryAlbum();
        }
        return $this->album;
    }
}