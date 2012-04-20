<?php

define('ROOT_PATH', dirname(__DIR__));
define('LIBRARY_PATH', realpath(ROOT_PATH . '/lib/Picgallery'));
define('GOOGLE_USER', 'antonio.haley@gmail.com');

$include_path = explode(':', get_include_path());
$include_path[] = LIBRARY_PATH;
$include_path[] = ROOT_PATH . '/vendor/Dropbox';
set_include_path(implode(':', $include_path));

require_once 'FileHelper.php';
