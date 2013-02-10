<?php

require_once 'SplClassLoader.php';
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/config.php';

$classLoader = new SplClassLoader('Picgallery', __DIR__);
$classLoader->register();
