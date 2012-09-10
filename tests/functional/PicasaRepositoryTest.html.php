<?php
session_start();
include '../Bootstrap.php';
require_once 'Picgallery.php';

$title = 'PicasaRepository functional test';

include 'header.php';

$googleSession = new \Picgallery\GoogleSession(GOOGLE_USER);
if (!$googleSession->hasGoogleToken()) {
    echo "Click <a href=\"" . $googleSession->getAuthUrl() .
        "\">here</a> to authorize with Picasaweb.";
    include 'footer.php';
    die();
}

$repository = \Picgallery\PicasaRepository::create($googleSession);

$albumRepository = $repository->getAlbumRepository();

$albumExists = $albumRepository->repositoryAlbumExists();

assert($albumExists);








include 'footer.php';
?>

