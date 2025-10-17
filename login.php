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
  <title>Login | Hotel Solemar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), 
                  url('imagens/imghotelsolemarlogin.png') no-repeat center center;
      background-size: cover;
      min-height: 120vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 4px 30px rgba(0,0,0,0.2);
      padding: 40px;
      max-width: 380px;
      width: 100%;
      color: #fff;
    }

    .login-card img {
      width: 100px;
      display: block;
      margin: 0 auto 20px;
    }

    .login-card h2 {
      text-align: center;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .login-card p.subtitle {
      text-align: center;
      color: #ddd;
      margin-bottom: 25px;
      font-size: 14px;
    }

    .form-control {
      border-radius: 10px;
      background-color: rgba(255,255,255,0.2);
      color: #fff;
      border: none;
    }

    .form-control::placeholder {
      color: #eee;
    }

    .btn-primary {
      background-color: #0078ff;
      border: none;
      border-radius: 10px;
      padding: 10px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #005fd1;
      transform: scale(1.03);
    }

    a.link-primary {
      color: #00c3ff;
      text-decoration: none;
    }

    a.link-primary:hover {
      text-decoration: underline;
    }

    .alert {
      background: rgba(255, 0, 0, 0.2);
      border: 1px solid rgba(255, 0, 0, 0.4);
      color: #fff;
    }
  </style>
</head>
<body>

  <div class="login-card">
    <img src="imagens/logo.png" alt="Hotel Solemar Logo">
    <h2>Bem-vindo</h2>
    <p class="subtitle">Acesse o painel de funcionários</p>

    <?php if (!empty($erro)) : ?>
      <div class="alert alert-danger text-center"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="mb-3">
        <input type="text" name="username" id="username" class="form-control" placeholder="Usuário" required>
      </div>

      <div class="mb-3">
        <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>

      <p class="mt-3 text-center">
        Não tem conta?
        <a href="registo.php" class="link-primary">Registe-se aqui</a>
      </p>
    </form>
  </div>

</body>
</html>
