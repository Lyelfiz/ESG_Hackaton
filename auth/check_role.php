<?php

require_once __DIR__ . '/check_auth.php';

function require_role(array|string $roles): void
{
    $roles = (array) $roles;
    $nivel = $_SESSION['usuario']['nivel'] ?? '';

    if (!in_array($nivel, $roles, true)) {
        http_response_code(403);
        $pageTitle = 'Acesso negado';
        require __DIR__ . '/../includes/header.php';
        require __DIR__ . '/../includes/navbar.php';
        echo '<main class="container"><section class="panel"><h1>Acesso negado</h1><p>Voce nao tem permissao para acessar esta pagina.</p></section></main>';
        require __DIR__ . '/../includes/footer.php';
        exit;
    }
}
