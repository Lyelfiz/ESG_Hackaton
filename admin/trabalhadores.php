<?php

require_once __DIR__ . '/../auth/check_role.php';
require_role('admin');
require_once __DIR__ . '/../config/database.php';

$busca = trim($_GET['busca'] ?? '');
$trabalhadores = [];

try {
    $pdo = getConnection();

    if ($busca !== '') {
        $stmt = $pdo->prepare(
            'SELECT id, nome, documento, cargo, setor, status
             FROM trabalhadores
             WHERE nome LIKE :busca OR documento LIKE :busca
             ORDER BY nome'
        );
        $stmt->execute(['busca' => '%' . $busca . '%']);
    } else {
        $stmt = $pdo->query('SELECT id, nome, documento, cargo, setor, status FROM trabalhadores ORDER BY nome');
    }

    $trabalhadores = $stmt->fetchAll();
} catch (Throwable $e) {
    $erroBanco = 'Nao foi possivel listar os trabalhadores.';
}

$pageTitle = 'Trabalhadores';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/navbar.php';
?>
<main class="container">
    <div class="page-title">
        <h1>Trabalhadores</h1>
        <p>Busca e consulta inicial dos perfis cadastrados.</p>
    </div>

    <section class="panel">
        <form class="search-form" method="get">
            <label for="busca">Buscar por nome, CPF ou matricula</label>
            <div>
                <input id="busca" name="busca" value="<?= h($busca) ?>" placeholder="Digite um termo">
                <button class="button" type="submit">Buscar</button>
            </div>
        </form>
    </section>

    <?php if (!empty($erroBanco)): ?>
        <div class="alert"><?= h($erroBanco) ?></div>
    <?php endif; ?>

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF/Matricula</th>
                        <th>Cargo</th>
                        <th>Setor</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trabalhadores as $trabalhador): ?>
                        <tr>
                            <td><?= h($trabalhador['nome']) ?></td>
                            <td><?= h($trabalhador['documento']) ?></td>
                            <td><?= h($trabalhador['cargo']) ?></td>
                            <td><?= h($trabalhador['setor']) ?></td>
                            <td><span class="badge"><?= h($trabalhador['status']) ?></span></td>
                            <td><a href="<?= h(app_url('admin/perfil_trabalhador.php?id=' . $trabalhador['id'])) ?>">Perfil</a></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$trabalhadores): ?>
                        <tr><td colspan="6">Nenhum trabalhador encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
