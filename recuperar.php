<?php
require "connection.php";
require "registo.php"; // aqui dentro já tens a função enviar_email()

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $codigo_aleatorio = bin2hex(random_bytes(16));

    $query = mysqli_prepare($conn, 
        "UPDATE funcionarios 
         SET RecoverCode = ?, RecoverExpirationTime = NOW() + INTERVAL 5 MINUTE 
         WHERE email = ?"
    );
    mysqli_stmt_bind_param($query, "ss", $codigo_aleatorio, $email);

    if (mysqli_stmt_execute($query) && mysqli_stmt_affected_rows($query) > 0) {
        $link_recuperacao = "http://localhost/5417HotelSolMar1009/resetpassword.php?code=" . $codigo_aleatorio;
        $assunto = "Recuperação de senha";
        $mensagem = "Clique no link para redefinir sua senha: 
                     <a href='" . $link_recuperacao . "'>Redefinir Senha</a>. 
                     O link é válido por 5 minutos.";
        
        enviar_email($email, "Funcionário", $assunto, $mensagem);
        echo "✅ Um email de recuperação foi enviado para " . htmlspecialchars($email);
    } else {
        echo "❌ Email não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Senha</title>
</head>
<body>
  <h2>Recuperar Senha</h2>
  <form method="post">
    <label for="email">Digite seu email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Enviar Link</button>
  </form>
</body>
</html>
