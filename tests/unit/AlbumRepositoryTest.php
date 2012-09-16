<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/AlbumRepository.php';

use Picgallery\AlbumRepository;

class AlbumRepositoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function ShouldCheckForRepoAlbumOnInstantiation()
    {
        $albumAdapter = $this->getMock('Picgallery\AlbumAdapter');
        $albumAdapter->expects($this->once())
            ->method('hasAlbum')
            ->with($this->equalTo('Picgallery'))
            ->will($this->returnValue(true));

        $albumRepo = new AlbumRepository($albumAdapter);

    }
}
