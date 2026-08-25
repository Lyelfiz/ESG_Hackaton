<?php

require_once __DIR__ . '/../auth/check_role.php';
require_role('trabalhador');
require_once __DIR__ . '/../config/database.php';

$resumo = ['total' => 0, 'concluidos' => 0, 'pendentes' => 0];

try {
    $pdo = getConnection();
    $stmt = $pdo->prepare(
        'SELECT tc.status
         FROM trabalhador_cursos tc
         INNER JOIN trabalhadores t ON t.id = tc.trabalhador_id
         WHERE t.usuario_id = :usuario_id'
    );
    $stmt->execute(['usuario_id' => $_SESSION['usuario']['id']]);
    $vinculos = $stmt->fetchAll();

    $resumo['total'] = count($vinculos);
    foreach ($vinculos as $vinculo) {
        if ($vinculo['status'] === 'concluido') {
            $resumo['concluidos']++;
        } else {
            $resumo['pendentes']++;
        }
    }
} catch (Throwable $e) {
    $erroBanco = 'Nao foi possivel carregar seu resumo.';
}

$pageTitle = 'Dashboard do trabalhador';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/navbar.php';
?>
<main class="container">
    <div class="page-title">
        <h1>Dashboard do trabalhador</h1>
        <p>Acompanhe seus treinamentos obrigatorios.</p>
    </div>

    <?php if (!empty($erroBanco)): ?>
        <div class="alert"><?= h($erroBanco) ?></div>
    <?php endif; ?>

    <section class="stats-grid">
        <article class="stat-card">
            <span>Cursos vinculados</span>
            <strong><?= $resumo['total'] ?></strong>
        </article>
        <article class="stat-card">
            <span>Concluidos</span>
            <strong><?= $resumo['concluidos'] ?></strong>
        </article>
        <article class="stat-card">
            <span>Pendentes</span>
            <strong><?= $resumo['pendentes'] ?></strong>
        </article>
    </section>

    <section class="panel">
        <h2>Meus cursos</h2>
        <p>Consulte status, conclusao e validade dos cursos vinculados ao seu cadastro.</p>
        <a class="button" href="<?= h(app_url('trabalhador/meus_cursos.php')) ?>">Ver cursos</a>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
