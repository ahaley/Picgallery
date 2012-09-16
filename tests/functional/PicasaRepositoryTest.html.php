<?php
session_start();
include '../Bootstrap.php';
require_once 'Picgallery.php';

$title = 'PicasaRepository functional test';

include 'header.php';

$googleSession = new \Picgallery\AuthSubGoogleSession(GOOGLE_USER);
if (!$googleSession->hasGoogleToken()) {
    echo "Click <a href=\"" . $googleSession->getAuthUrl() .
        "\">here</a> to authorize with Picasaweb.";
    include 'footer.php';
    die();
}

$repository = \Picgallery\PicasaRepository::create($googleSession);

$images = $repository->getImages();

foreach ($images as $image) {
?>
    <p><?= $image->title ?></p>
    <img src="<?= $image->thumbnail ?>"/>
<?php
}



print_r($images);


include 'footer.php';
?>

