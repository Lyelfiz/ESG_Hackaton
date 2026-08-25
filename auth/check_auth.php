<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['usuario'])) {
    header('Location: ' . app_url('auth/login.php'));
    exit;
}
