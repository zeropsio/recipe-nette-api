<?php

return [
    'database' => [
        'dsn' => \getenv('DB_DSN') ?: 'mysql:host=localhost;dbname=test',
        'user' => \getenv('DB_USERNAME') ?: 'root',
        'password' => \getenv('DB_PASSWORD') ?: '',
    ]
];
