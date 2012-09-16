<?php

namespace Picgallery;

interface AlbumAdapter
{
    public function hasAlbum($albumName);
    public function getAlbum($albumName);
    public function createAlbum($albumName, $summary = null);
    public function getAlbums();
    public function getAlbumFeed($albumName);
}
