<?php
session_start();
include '../Bootstrap.php';
require_once 'Picgallery.php';

$title = 'PicasaRepository functional test';

include 'header.php';


if (isset($_SESSION['my_token']) ) {
    echo "SESSION['my_token'] is set to " . $_SESSION['my_token']
        . "<br>";
}
else {
    echo "SESSION['my_token'] is not set<br>";
}

$_SESSION['my_token'] = "abcdefg";
$googleSession = new \Picgallery\GoogleSession;
$repository = \Picgallery\PicasaRepository::create(GOOGLE_USER, $googleSession);

if ($repository === null) {
    echo "Click <a href=\"" . $googleSession->getAuthUrl() .
        "\">here</a> to authorize with Picasaweb.";
    include 'footer.php';
    die();
}

$albumRepository = $repository->getAlbumRepository();

$albums = $albumRepository->getAlbums();

foreach ($albums as $name => $entry) {
    echo "name = " . $name . '<br>';
    echo "num items = " . $entry->getGphotoNumPhotos() . "<br><br>";
}

include 'footer.php';
?>

