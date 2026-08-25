<?php

// Copie este arquivo para database.php e escolha o tipo desejado.
// Opcoes: mysql, pgsql, sqlite.
$databaseType = 'mysql';

$settings = [
    // XAMPP e Laragon geralmente usam estes mesmos dados locais.
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
        'password' => 'sua_senha',
    ],
    'sqlite' => [
        'path' => __DIR__ . '/../database/database.sqlite',
    ],
];

function getConnection(): PDO
{
    global $databaseType, $settings;

    $config = $settings[$databaseType];

    if ($databaseType === 'mysql') {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    } elseif ($databaseType === 'pgsql') {
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
    } else {
        $dsn = 'sqlite:' . $config['path'];
    }

    return new PDO($dsn, $config['username'] ?? null, $config['password'] ?? null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
