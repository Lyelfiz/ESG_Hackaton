<?php

session_start();
require_once __DIR__ . '/includes/functions.php';

if (!empty($_SESSION['usuario'])) {
    $destino = $_SESSION['usuario']['nivel'] === 'admin' ? 'admin/dashboard.php' : 'trabalhador/dashboard.php';
    header('Location: ' . app_url($destino));
    exit;
}

header('Location: ' . app_url('auth/login.php'));
exit;
