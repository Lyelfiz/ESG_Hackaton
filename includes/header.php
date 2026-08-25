<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'Treinamento e Seguranca';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= h(app_url('assets/css/style.css')) ?>">
</head>
<body>
