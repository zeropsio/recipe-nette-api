<?php

return [
    'nettrine.dbal' => [
        'connection' => [
            'host' => \getenv('db_hostname') ?: 'mariadb',
            'driver' => \getenv('DB_DRIVER') ?: 'pdo_mysql',
            'dbname' => \getenv('db_hostname') ?: 'db',
            'user' => \getenv('db_hostname') ?: 'db',
            'password' => \getenv('db_password') ?: 'password',
        ]
    ]
];
