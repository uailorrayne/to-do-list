<?php
session_start();
require_once('database/conn.php');

// Se já estiver logado, manda para a página inicial
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail já está cadastrado
    $sql = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $sql->bindValue(':email', $email);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $erro = "E-mail já cadastrado!";
    } else {

        // Hash seguro da senha
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere no banco
        $insert = $pdo->prepare("
            INSERT INTO users (username, email, password_hash)
            VALUES (:username, :email, :password)
        ");
        $insert->bindValue(':username', $username);
        $insert->bindValue(':email', $email);
        $insert->bindValue(':password', $hash);
        $insert->execute();

        // Redireciona após cadastro
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="src/styles/style.css">
</head>

<body>
<div id="to_do" style="max-width: 350px;">
    <h1>Registrar</h1>

    <?php if (isset($erro)): ?>
        <p style="color: #ee9ca7; font-size: 15px; margin-bottom: 10px;">
            <?= $erro ?>
        </p>
    <?php endif; ?>

    <form method="POST" class="to-do-form" style="flex-direction: column; gap: 20px;">

        <input type="text"
               name="username"
               placeholder="Nome de usuário"
               required
               style="border-bottom: 2px solid #6c9bbc;">

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
        Já tem conta?
        <a href="login.php" style="color:#6c9bbc;">Fazer login</a>
    </p>

</div>
</body>
</html>
