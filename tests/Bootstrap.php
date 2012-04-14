<?php

define('LIBRARY_PATH', dirname(__DIR__));

$include_path = explode(':', get_include_path());
$include_path[] = LIBRARY_PATH;
set_include_path(implode(':', $include_path));

require_once LIBRARY_PATH . '/Helper/FileHelper.php';
require_once 'PHPUnit/Framework/TestCase.php';
