<?php
include "connection.php";
require "email.php"; // 👉 usa a função enviar_email daqui

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $cargo = trim($_POST["cargo"]);
    $username = trim($_POST["username"]);
    $senha = trim($_POST["senha"]);

    if (empty($nome) || empty($email) || empty($username) || empty($senha)) {
        $erro = "Preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "O email não é válido.";
    } else {
        // Verifica email duplicado
        $stmtCheck = mysqli_prepare($conn, "SELECT id FROM funcionarios WHERE email=?");
        mysqli_stmt_bind_param($stmtCheck, "s", $email);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            $erro = "Este email já está cadastrado!";
        } else {
            // Verifica username duplicado
            $stmtUser = mysqli_prepare($conn, "SELECT id FROM funcionarios WHERE username=?");
            mysqli_stmt_bind_param($stmtUser, "s", $username);
            mysqli_stmt_execute($stmtUser);
            mysqli_stmt_store_result($stmtUser);

            if (mysqli_stmt_num_rows($stmtUser) > 0) {
                $erro = "Este username já está em uso!";
            } else {
                // Inserir novo funcionário
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($conn, "INSERT INTO funcionarios (nome, cargo, email, username, senha, data_admissao) VALUES (?, ?, ?, ?, ?, CURDATE())");
                mysqli_stmt_bind_param($stmt, "sssss", $nome, $cargo, $email, $username, $senhaHash);

                if (mysqli_stmt_execute($stmt)) {
                    $sucesso = "Conta criada com sucesso!";

                    // Enviar email de boas-vindas
                    $mensagem = "
                        <h3>Olá $nome,</h3>
                        <p>Sua conta foi criada com sucesso no <b>Sistema do Hotel Sol&Mar</b>.</p>
                        <p><b>Username:</b> $username</p>
                        <br>
                        <p>Obrigado por se juntar a nós!</p>
                    ";
                    enviar_email($email, $nome, "Bem-vindo ao Sistema do Hotel Sol&Mar", $mensagem);
                } else {
                    $erro = "Erro ao criar conta: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_stmt_close($stmtUser);
        }
        mysqli_stmt_close($stmtCheck);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo de Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Criar Nova Conta</h2>

        <?php if (!empty($erro)) : ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if (!empty($sucesso)) : ?>
            <div class="alert alert-success"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" name="cargo" id="cargo" class="form-control">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Registar</button>
            <a href="login.php" class="btn btn-secondary">Já tenho conta</a>
        </form>
    </div>
</div>
</body>
</html>
