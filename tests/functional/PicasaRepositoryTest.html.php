<?php
include '../Bootstrap.php';
require_once 'Picgallery.php';

$title = 'PicasaRepository functional test';

include 'header.php';

$googleSession = new \Picgallery\GoogleSession;
$repository = \Picgallery\PicasaRepository::create(GOOGLE_USER, $googleSession);

if ($repository !== null) {
	echo "repositroy failed";
}
else {
	echo "repository success";
}

$albumRepository = $repository->getAlbumRepository();

$albums = $albumRepository->getAlbums();

foreach ($albums as $name => $entry) {
    echo "name = " . $name . '<br>';
    echo "num items = " . $entry->getGphotoNumPhotos() . "<br><br>";
}

include 'footer.php';
?>

