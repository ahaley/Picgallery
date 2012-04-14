<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once __DIR__ . '/../../DoctrineLoader.php';

class DoctrineLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $em = DoctrineLoader::Load();

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $em);
    }
}
