<?php

require_once 'SplClassLoader.php';

$classLoader = new SplClassLoader('Picgallery', __DIR__);
$classLoader->register();
