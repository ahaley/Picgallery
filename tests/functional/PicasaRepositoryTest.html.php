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


if (isset($_GET['photoid'])) {
    $image = $repository->getImage(intval($_GET['photoid']));
?>
<img src="<?= $image->image_url ?>"/>

<?php
}

$images = $repository->getImages();

foreach ($images as $image) {
?>
    <p><?= $image->title ?></p>
    <a href="PicasaRepositoryTest.html.php/?photoid=<?= $image->id ?>">
        <img src="<?= $image->thumbnail ?>"/>
    </a>
<?php
}

include 'footer.php';
?>

