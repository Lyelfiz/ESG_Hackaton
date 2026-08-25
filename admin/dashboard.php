<?php

require_once __DIR__ . '/../auth/check_role.php';
require_role('admin');
require_once __DIR__ . '/../config/database.php';

$totalTrabalhadores = 0;
$totalCursos = 0;
$cursosVencidos = 0;

try {
    $pdo = getConnection();
    $totalTrabalhadores = (int) $pdo->query('SELECT COUNT(*) FROM trabalhadores')->fetchColumn();
    $totalCursos = (int) $pdo->query('SELECT COUNT(*) FROM cursos')->fetchColumn();
    $cursosVencidos = (int) $pdo->query("SELECT COUNT(*) FROM trabalhador_cursos WHERE validade IS NOT NULL AND validade < CURRENT_DATE")->fetchColumn();
} catch (Throwable $e) {
    $erroBanco = 'Nao foi possivel carregar os indicadores.';
}

$pageTitle = 'Dashboard administrativo';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/navbar.php';
?>
<main class="container">
    <div class="page-title">
        <h1>Dashboard administrativo</h1>
        <p>Visao inicial para acompanhar trabalhadores e treinamentos.</p>
    </div>

    <?php if (!empty($erroBanco)): ?>
        <div class="alert"><?= h($erroBanco) ?></div>
    <?php endif; ?>

    <section class="stats-grid">
        <article class="stat-card">
            <span>Trabalhadores</span>
            <strong><?= $totalTrabalhadores ?></strong>
        </article>
        <article class="stat-card">
            <span>Cursos cadastrados</span>
            <strong><?= $totalCursos ?></strong>
        </article>
        <article class="stat-card">
            <span>Validades vencidas</span>
            <strong><?= $cursosVencidos ?></strong>
        </article>
    </section>

    <section class="panel">
        <h2>Atalhos</h2>
        <p>Use a area administrativa para localizar trabalhadores e consultar cursos vinculados.</p>
        <a class="button" href="<?= h(app_url('admin/trabalhadores.php')) ?>">Ver trabalhadores</a>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
