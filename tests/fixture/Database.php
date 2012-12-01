<?php

namespace tests\fixture;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class Database
{
    public static function createDoctrineConnection()
    {
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

}
