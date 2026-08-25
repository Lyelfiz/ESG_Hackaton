<?php

require_once __DIR__ . '/../auth/check_role.php';
require_role('admin');
require_once __DIR__ . '/../config/database.php';

$id = (int) ($_GET['id'] ?? 0);
$trabalhador = null;
$cursos = [];

try {
    $pdo = getConnection();

    $stmt = $pdo->prepare('SELECT id, nome, documento, cargo, setor, status FROM trabalhadores WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $trabalhador = $stmt->fetch();

    if ($trabalhador) {
        $stmt = $pdo->prepare(
            'SELECT c.nome, tc.status, tc.data_conclusao, tc.validade
             FROM trabalhador_cursos tc
             INNER JOIN cursos c ON c.id = tc.curso_id
             WHERE tc.trabalhador_id = :id
             ORDER BY c.nome'
        );
        $stmt->execute(['id' => $id]);
        $cursos = $stmt->fetchAll();
    }
} catch (Throwable $e) {
    $erroBanco = 'Nao foi possivel carregar o perfil.';
}

$pageTitle = 'Perfil do trabalhador';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/navbar.php';
?>
<main class="container">
    <div class="page-title">
        <h1>Perfil do trabalhador</h1>
        <p>Dados basicos e cursos vinculados.</p>
    </div>

    <?php if (!empty($erroBanco)): ?>
        <div class="alert"><?= h($erroBanco) ?></div>
    <?php elseif (!$trabalhador): ?>
        <section class="panel"><p>Trabalhador nao encontrado.</p></section>
    <?php else: ?>
        <section class="profile-grid">
            <article class="panel">
                <h2><?= h($trabalhador['nome']) ?></h2>
                <dl class="details">
                    <dt>CPF/Matricula</dt><dd><?= h($trabalhador['documento']) ?></dd>
                    <dt>Cargo</dt><dd><?= h($trabalhador['cargo']) ?></dd>
                    <dt>Setor</dt><dd><?= h($trabalhador['setor']) ?></dd>
                    <dt>Status</dt><dd><?= h($trabalhador['status']) ?></dd>
                </dl>
            </article>

            <article class="panel">
                <h2>Cursos vinculados</h2>
                <div class="course-list">
                    <?php foreach ($cursos as $curso): ?>
                        <?php $situacao = course_situation($curso['status'], $curso['validade']); ?>
                        <div class="course-item">
                            <strong><?= h($curso['nome']) ?></strong>
                            <span>Status: <?= h($curso['status']) ?></span>
                            <span>Conclusao: <?= h(format_date($curso['data_conclusao'])) ?></span>
                            <span>Validade: <?= h(format_date($curso['validade'])) ?></span>
                            <span class="badge badge-<?= h($situacao) ?>"><?= h($situacao) ?></span>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!$cursos): ?>
                        <p>Nenhum curso vinculado.</p>
                    <?php endif; ?>
                </div>
            </article>
        </section>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
