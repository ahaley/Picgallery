<?php

namespace tests\fixture;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class Database
{
    public static function createDoctrineConnection()
    {
        self::_reloadDatabase();
        $config = new Configuration();
        $params = array(
            'dbname' => 'picgallery_integration',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD')
        );
        return DriverManager::getConnection($params, $config);
    }

    private static function _reloadDatabase()
    {
        chdir(ROOT_PATH . '/db');
        system('./reload_integration.sh');
    }

}
