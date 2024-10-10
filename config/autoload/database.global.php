<?php

use Doctrine\DBAL\DriverManager;

return [
    'dependencies' => [
        'factories' => [
            'db' => function ($container) {
                $config = $container->get('config')['database'];

                $connectionParams = [
                    'dbname'   => $config['name'],
                    'user'     => $config['user'],
                    'password' => $config['pass'],
                    'host'     => $config['host'],
                    'driver'   => match ($config['type']) {
                        'pgsql'  => 'pdo_pgsql',
                        'mysql'  => 'pdo_mysql',
                        'sqlite' => 'pdo_sqlite',
                        default  => throw new \Exception('Unsupported database driver')
                    },
                ];

                return DriverManager::getConnection($connectionParams);
            }
        ],
    ],

    'database' => [
        'type'   => getenv('DB_DRIVER'),    // Use the DB_DRIVER environment variable
        'host'   => getenv('DB_HOST'),
        'name'   => getenv('DB_DATABASE'),
        'user'   => getenv('DB_USERNAME'),
        'pass'   => getenv('DB_PASSWORD'),
    ],
];
