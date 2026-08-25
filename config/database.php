<?php

// Troque este valor para: mysql, pgsql ou sqlite.
$databaseType = 'mysql';

$settings = [
    'mysql' => [
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'treinamento_seguranca',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    'pgsql' => [
        'host' => 'localhost',
        'port' => '5432',
        'database' => 'treinamento_seguranca',
        'username' => 'postgres',
        'password' => '',
    ],
    'sqlite' => [
        'path' => __DIR__ . '/../database/database.sqlite',
    ],
];

function getConnection(): PDO
{
    global $databaseType, $settings;

    if (!isset($settings[$databaseType])) {
        throw new RuntimeException('Tipo de banco invalido.');
    }

    $config = $settings[$databaseType];

    if ($databaseType === 'mysql') {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
    } elseif ($databaseType === 'pgsql') {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $config['host'],
            $config['port'],
            $config['database']
        );
    } else {
        $dsn = 'sqlite:' . $config['path'];
    }

    $username = $config['username'] ?? null;
    $password = $config['password'] ?? null;

    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
