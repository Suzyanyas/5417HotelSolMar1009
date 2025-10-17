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
    <title>Registo de Funcionário - Hotel Sol&Mar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('imagens/background2.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .container-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            margin: 60px auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        .button-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 10px 20px;
            min-width: 150px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container-form">
    <h2 class="mb-4 text-center">Criar Nova Conta</h2>

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

        <div class="button-row">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Registar
            </button>

            <a href="login.php" class="btn btn-secondary">
                <i class="bi bi-box-arrow-in-right"></i> Fazer Login
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
