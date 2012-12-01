<?php

namespace tests\fixture;

class Database
{
    public static function createDoctrineConnection()
    {
        $config = new \Doctrine\DBAL\Configuration();
        $params = array(
            'dbname' => 'picgallery_integration',
            'host' => 'localhost',
            'driver' => 'pdo_mysql';
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD')
        );
        return \Doctrine\DBAL\DriverManager::getConnection($params, $config);
    }

}
