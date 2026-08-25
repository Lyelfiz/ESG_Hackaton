<?php

require_once __DIR__ . '/../auth/check_role.php';
require_role('trabalhador');
require_once __DIR__ . '/../config/database.php';

$cursos = [];

try {
    $pdo = getConnection();
    $stmt = $pdo->prepare(
        'SELECT c.nome, tc.status, tc.data_conclusao, tc.validade
         FROM trabalhador_cursos tc
         INNER JOIN cursos c ON c.id = tc.curso_id
         INNER JOIN trabalhadores t ON t.id = tc.trabalhador_id
         WHERE t.usuario_id = :usuario_id
         ORDER BY c.nome'
    );
    $stmt->execute(['usuario_id' => $_SESSION['usuario']['id']]);
    $cursos = $stmt->fetchAll();
} catch (Throwable $e) {
    $erroBanco = 'Nao foi possivel carregar seus cursos.';
}

$pageTitle = 'Meus cursos';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/navbar.php';
?>
<main class="container">
    <div class="page-title">
        <h1>Meus cursos</h1>
        <p>Lista de treinamentos vinculados ao seu perfil.</p>
    </div>

    <?php if (!empty($erroBanco)): ?>
        <div class="alert"><?= h($erroBanco) ?></div>
    <?php endif; ?>

    <section class="panel">
        <div class="course-list">
            <?php foreach ($cursos as $curso): ?>
                <?php $situacao = course_situation($curso['status'], $curso['validade']); ?>
                <article class="course-item">
                    <strong><?= h($curso['nome']) ?></strong>
                    <span>Status: <?= h($curso['status']) ?></span>
                    <span>Data de conclusao: <?= h(format_date($curso['data_conclusao'])) ?></span>
                    <span>Validade: <?= h(format_date($curso['validade'])) ?></span>
                    <span class="badge badge-<?= h($situacao) ?>"><?= h($situacao) ?></span>
                </article>
            <?php endforeach; ?>

            <?php if (!$cursos): ?>
                <p>Nenhum curso vinculado ao seu usuario.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
