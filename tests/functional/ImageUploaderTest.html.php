<?php
session_start();
include '../Bootstrap.php';
require_once 'Picgallery.php';

use Picgallery\LocalImageRepository;

$title = 'ImageUploader functional test';

include 'header.php';

$upload_name = 'uploadedfile';

$local_path = 'upload';	
$url_path = dirname($_SERVER['PHP_SELF']) . '/' . $local_path;

$repository = new LocalImageRepository($local_path, $url_path);

if (isset($_FILES[$upload_name])) {
    $name = $_FILES[$upload_name]['name'];
    $tmp_name = $_FILES[$upload_name]['tmp_name'];
    $type = $_FILES[$upload_name]['type'];
    
    $repository->uploadImage($name, $type, $tmp_name);

    if (file_exists('upload/' . $name)) {
        echo "Uploaded file exists!<br>";
    }
    else {
        echo "Uploaded file does not exist<br>";
    }
}

?>
<form enctype="multipart/form-data" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="6000000" />
    File to upload: <input name="<?= $upload_name ?>" type="file" />
    <input type="submit" value="Send File" />
</form>
<?php

$images = $repository->getImages();

echo "There are " . count($images) . " images.<br>";

foreach ($images as $image) {
?>
<img src="<?= $image->getThumbnailUrl() ?>">
<?php
}


include 'footer.php';
?>

