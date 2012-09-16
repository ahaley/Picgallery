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
            ->method('getAlbum')
            ->with($this->equalTo('Picgallery'))
            ->will($this->returnValue(new stdClass));
        $albumRepo = new AlbumRepository($albumAdapter);
    }

    /**
     * @test
     */
    public function ShouldCreateAlbumIfNotFound()
    {
        $albumAdapter = $this->getMock('Picgallery\AlbumAdapter');
        $albumAdapter->expects($this->at(1))
            ->method('getAlbum')
            ->will($this->returnValue(null));
        $albumAdapter->expects($this->at(2))
            ->method('getAlbum')
            ->will($this->returnValue(new stdClass));
        $albumAdapter->expects($this->once())
            ->method('createAlbum')
            ->with('Picgallery');

        $albumRepo = new AlbumRepository($albumAdapter);
    }

    /**
     * @test
     */
    public function ShouldThrowExceptionIfAlbumNotCreated()
    {
        $albumAdapter = $this->getMock('Picgallery\AlbumAdapter');
        $albumAdapter->expects($this->exactly(3))
            ->method('getAlbum')
            ->will($this->returnValue(null));

        try {
        $albumRepo = new AlbumRepository($albumAdapter);
        }
        catch (Exception $ex) {
            return;
        }
        $this->fail('Did not throw exception');
    }
}
