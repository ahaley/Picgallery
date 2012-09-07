<?php

namespace Picgallery;

interface ImageAlbumRepository
{
    public function repositoryAlbumExists();
    public function createRepositoryAlbum();
    public function getAlbums();
    public function getRepositoryAlbumFeed();
    public function getRepositoryAlbumEntry();
}
