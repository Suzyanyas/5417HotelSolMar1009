<?php
include "connection.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $senha = trim($_POST["senha"]);

    if (empty($username) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } else {
        // Procurar utilizador na BD
        $query = "SELECT * FROM funcionarios WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verifica a senha (encriptada com password_hash no registo)
            if (password_verify($senha, $row["senha"])) {
                session_start();
                $_SESSION["funcionario_id"] = $row["id"];
                $_SESSION["nome"] = $row["nome"];
                $_SESSION["cargo"] = $row["cargo"];

                header("Location: reservas.php");
                exit();
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "Funcionário não encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: url('imagens/hotelsolemarlogin.png') no-repeat center center;
    background-size: 100% 100%; /* força a imagem a preencher toda a tela */
    background-attachment: scroll;
    min-height: 100vh;
}


</style>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4 col-md-4 mx-auto">
    <h2 class="mb-4 text-center">Login</h2>

    <?php if (!empty($erro)) : ?>
      <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" name="senha" id="senha" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>

      <p class="mt-3 text-center">
            Não tem conta? 
            <a href="registo.php" class="link-primary">Registe-se aqui</a>
      </p>

    </form>
  </div>
</div>

</body>
</html>
