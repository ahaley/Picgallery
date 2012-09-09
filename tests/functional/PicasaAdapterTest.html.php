<!DOCTYPE html>
<html>
<head>
<title>Picasa Adapter Test</title>
</head>
<body>
<?php
include '../Bootstrap.php';
require_once 'Picgallery.php';

$googleSession = new \Picgallery\GoogleSession;
$repository = \Picgallery\PicasaRepository::create(GOOGLE_USER, $googleSession);

if ($repository !== null) {
	echo "repositroy failed";
}
else {
	echo "repository success";
}

?>
</body>
</html>

