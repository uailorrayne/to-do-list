<?php
session_start();
require_once('database/conn.php');

// Se já estiver logado, manda para a lista
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// PROCESSAMENTO DO LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco
    $sql = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $sql->bindValue(':email', $email);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        // Verifica senha com hash
        if (password_verify($senha, $user['password_hash'])) {

            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="src/styles/style.css">
</head>

<body>

<div id="to_do" style="max-width: 350px;">
    <h1>Login</h1>

    <?php if (isset($erro)): ?>
        <p style="color: #ee9ca7; font-size: 15px; margin-bottom: 10px;">
            <?= $erro ?>
        </p>
    <?php endif; ?>

    <form method="POST" class="to-do-form" style="flex-direction: column; gap: 20px;">

        <input type="email"
               name="email"
               placeholder="E-mail"
               required
               style="border-bottom: 2px solid #6c9bbc;">

        <input type="password"
               name="senha"
               placeholder="Senha"
               required
               style="border-bottom: 2px solid #6c9bbc;">

        <button type="submit" class="form-button" style="align-self: center;">
            <i class="fa-solid fa-check"></i>
        </button>

    </form>

    <p style="margin-top: 15px; text-align:center;">
        Ainda não tem conta?
        <a href="register.php" style="color:#6c9bbc;">Criar conta</a>
    </p>

</div>

</body>
</html>
