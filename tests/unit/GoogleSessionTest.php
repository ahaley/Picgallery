<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Picgallery/GoogleSession.php';

use Picgallery\GoogleSession;
use Picgallery\AuthSubGoogleSession;

class GoogleSessionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function AuthSubShouldAcceptAndRetrieveUsername()
    {
        $username = 'user1';
        $googleSession = new AuthSubGoogleSession($username);
        $this->assertEquals($username, $googleSession->getUsername());
    }
}
