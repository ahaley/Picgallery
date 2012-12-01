<?php

namespace tests\unit;

use \Picgallery\AlbumRepository;

class AlbumRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Picgallery\AlbumRepository::__construct
     */
    public function ShouldCheckForRepoAlbumOnInstantiation()
    {
        $albumAdapter = $this->getMock('\Picgallery\AlbumAdapter');
        $albumAdapter->expects($this->once())
            ->method('getAlbum')
            ->with($this->equalTo('Picgallery'))
            ->will($this->returnValue(new \stdClass));
        $albumRepo = new AlbumRepository($albumAdapter);
    }

    /**
     * @test
     * @covers Picgallery\AlbumRepository::__construct
     */
    public function ShouldCreateAlbumIfNotFound()
    {
        $albumAdapter = $this->getMock('\Picgallery\AlbumAdapter');
        $albumAdapter->expects($this->at(1))
            ->method('getAlbum')
            ->will($this->returnValue(null));
        $albumAdapter->expects($this->at(2))
            ->method('getAlbum')
            ->will($this->returnValue(new \stdClass));
        $albumAdapter->expects($this->once())
            ->method('createAlbum')
            ->with('Picgallery');

        $albumRepo = new AlbumRepository($albumAdapter);
    }

    /**
     * @test
     * @covers Picgallery\AlbumRepository::__construct
     * @expectedException Exception
     * @expectedExceptionMessage Could not retrieve album
     */
    public function ShouldThrowExceptionIfAlbumNotCreated()
    {
        $albumAdapter = $this->getMock('Picgallery\AlbumAdapter');
        $albumAdapter->expects($this->exactly(3))
            ->method('getAlbum')
            ->will($this->returnValue(null));

        $albumRepo = new AlbumRepository($albumAdapter);
    }
}
