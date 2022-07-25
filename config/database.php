<?php

return [
    'nettrine.dbal' => [
        'connection' => [
            'host' => \getenv('DB_HOSTNAME') ?: 'mariadb',
            'driver' => \getenv('DB_DRIVER') ?: 'pdo_mysql',
            'dbname' => \getenv('DB_HOSTNAME') ?: 'db',
            'user' => \getenv('DB_HOSTNAME') ?: 'db',
            'password' => \getenv('DB_PASSWORD') ?: 'password',
        ]
    ]
];
