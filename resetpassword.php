<?php
require "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'], $_POST['confirm_password'], $_POST['code'])) {
    if ($_POST["new_password"] === $_POST["confirm_password"]) {
        $password_encripted = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

        $query = mysqli_prepare($conn,
            "UPDATE funcionarios 
             SET senha = ?, RecoverCode = NULL, RecoverExpirationTime = NULL 
             WHERE RecoverCode = ? AND RecoverExpirationTime >= NOW()"
        );
        mysqli_stmt_bind_param($query, "ss", $password_encripted, $_POST['code']);

        if (mysqli_stmt_execute($query) && mysqli_stmt_affected_rows($query) > 0) {
            echo "✅ Senha redefinida com sucesso. <a href='login.php'>Ir para Login</a>";
        } else {
            echo "❌ Código inválido ou expirado.";
        }
    } else {
        echo "⚠️ As senhas não coincidem.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Nova Senha</title>
</head>
<body>
  <h2>Defina uma nova senha</h2>
  <form action="resetpassword.php" method="post">
    <label for="new_password">Nova Senha:</label>
    <input type="password" id="new_password" name="new_password" required>
    <br>
    <label for="confirm_password">Confirmar Senha:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <br>
    <input type="hidden" name="code" value="<?php echo htmlspecialchars($_GET['code'] ?? ''); ?>">
    <button type="submit">Redefinir</button>
  </form>
</body>
</html>
