<!DOCTYPE html>
<html>
<head>
<title>Picasa Adapter Test</title>
</head>
<body>
<?php
include '../Bootstrap.php';
require_once 'GoogleSession.php';
require_once 'PicasaAdapter.php';

$googleSession = new \Picgallery\GoogleSession;
$adapter = \Picgallery\PicasaAdapter::create(GOOGLE_USER, $googleSession);

if ($adapter !== null) {
	echo "adapter failed";
}
else {
	echo "adapter success";
}

?>
</body>
</html>

