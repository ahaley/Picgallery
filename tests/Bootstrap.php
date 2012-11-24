<?php

define('ROOT_PATH', dirname(__DIR__));
define('LIBRARY_PATH', realpath(ROOT_PATH . '/lib'));
$google_user = getenv('GOOGLE_USER');
if (!$google_user) {
    echo <<<EOT

Please set the environment variable GOOGLE_USER if you with to run
functional tests.


EOT;
}
else {
define('GOOGLE_USER', getenv('GOOGLE_USER'));
}

$include_path = explode(':', get_include_path());
$include_path[] = LIBRARY_PATH;
$include_path[] = ROOT_PATH . '/vendor/Dropbox';
set_include_path(implode(':', $include_path));

require_once 'Picgallery/FileHelper.php';
