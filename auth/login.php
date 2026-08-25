<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['usuario'])) {
    $destino = $_SESSION['usuario']['nivel'] === 'admin' ? 'admin/dashboard.php' : 'trabalhador/dashboard.php';
    header('Location: ' . app_url($destino));
    exit;
}

$erro = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Informe email e senha.';
    } else {
        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare('SELECT id, nome, email, senha, nivel, ativo FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && (int) $usuario['ativo'] === 1 && password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true);

                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'nivel' => $usuario['nivel'],
                ];

                $destino = $usuario['nivel'] === 'admin' ? 'admin/dashboard.php' : 'trabalhador/dashboard.php';
                header('Location: ' . app_url($destino));
                exit;
            }

            $erro = 'Usuario ou senha invalidos.';
        } catch (Throwable $e) {
            $erro = 'Nao foi possivel conectar ao banco. Verifique config/database.php.';
        }
    }
}

$pageTitle = 'Login';
require __DIR__ . '/../includes/header.php';
?>
<main class="login-page">
    <section class="login-card">
        <h1>Controle de Treinamento</h1>
        <p>Acesse sua area de seguranca do trabalhador.</p>

        <?php if ($erro): ?>
            <div class="alert"><?= h($erro) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?= h($email) ?>" required>

            <label for="senha">Senha</label>
            <input id="senha" name="senha" type="password" required>

            <button class="button" type="submit">Entrar</button>
        </form>

        <div class="test-users">
            <strong>Teste:</strong>
            <span>admin@teste.com / 123456</span>
            <span>trabalhador@teste.com / 123456</span>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
