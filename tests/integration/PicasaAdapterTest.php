<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../../PicasaAdapter.php';

class PicasaAdapterTest extends PHPUnit_Framework_TestCase
{
    public function test_Adapter_Can_List_Images()
    {
    	$adapter = new PicasaAdapter;
    	$adapter->getAlbums();    
    }
}
