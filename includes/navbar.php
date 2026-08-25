<?php
require_once __DIR__ . '/functions.php';
$usuario = current_user();
?>
<header class="topbar">
    <a class="brand" href="<?= h(app_url('index.php')) ?>">Treinamento Seguro</a>

    <?php if ($usuario): ?>
        <nav class="nav">
            <?php if ($usuario['nivel'] === 'admin'): ?>
                <a href="<?= h(app_url('admin/dashboard.php')) ?>">Dashboard</a>
                <a href="<?= h(app_url('admin/trabalhadores.php')) ?>">Trabalhadores</a>
            <?php else: ?>
                <a href="<?= h(app_url('trabalhador/dashboard.php')) ?>">Dashboard</a>
                <a href="<?= h(app_url('trabalhador/meus_cursos.php')) ?>">Meus cursos</a>
            <?php endif; ?>
        </nav>

        <div class="user-menu">
            <span><?= h($usuario['nome']) ?></span>
            <a class="button button-light" href="<?= h(app_url('auth/logout.php')) ?>">Sair</a>
        </div>
    <?php endif; ?>
</header>
