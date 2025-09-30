<?php
include __DIR__ . '/connection.php';

$cliente = $quarto = $checkin = $checkout = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cliente = trim($_POST["cliente"]);
    $quarto = intval($_POST["quarto"]);
    $checkin = $_POST["checkin"];
    $checkout = $_POST["checkout"];

    // Validações
    if (empty($cliente)) {
        $erro = "O nome do cliente é obrigatório.";
    } elseif (empty($quarto)) {
        $erro = "O número do quarto é obrigatório.";
    } elseif (empty($checkin) || empty($checkout)) {
        $erro = "As datas de check-in e check-out são obrigatórias.";
    } elseif ($checkout < $checkin) {
        $erro = "A data de check-out não pode ser anterior à data de check-in.";
    }
    $query_check = "SELECT * FROM reservas 
        WHERE quarto = ? 
        AND estado = 'Ativa' 
        AND checkin <= ? 
        AND checkout >= ?";
        $stmt_check = mysqli_prepare($conn, $query_check);
        mysqli_stmt_bind_param($stmt_check, "iss", $quarto, $checkout, $checkin);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) > 0) {
            $erro = "Já existe uma reserva ativa para este quarto nas datas selecionadas.";
        } else {
        }

    if (empty($erro)) {
        $query = "INSERT INTO reservas (cliente, quarto, checkin, checkout, estado) 
                  VALUES (?, ?, ?, ?, 'Ativa')";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "siss", $cliente, $quarto, $checkin, $checkout);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: reservas.php");
            exit();
        } else {
            $erro = "Erro ao salvar a reserva: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Reserva - Hotel Sol&Mar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Nova Reserva</h2>

    <?php if (!empty($erro)) : ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="cliente" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" id="cliente" name="cliente" 
                   value="<?php echo htmlspecialchars($cliente); ?>" required>
        </div>

        <div class="mb-3">
            <label for="quarto" class="form-label">Número do Quarto</label>
            <input type="number" class="form-control" id="quarto" name="quarto" 
                   value="<?php echo htmlspecialchars($quarto); ?>" required>
        </div>

        <div class="mb-3">
            <label for="checkin" class="form-label">Data de Check-in</label>
            <input type="date" class="form-control" id="checkin" name="checkin" 
                   value="<?php echo htmlspecialchars($checkin); ?>" required>
        </div>

        <div class="mb-3">
            <label for="checkout" class="form-label">Data de Check-out</label>
            <input type="date" class="form-control" id="checkout" name="checkout" 
                   value="<?php echo htmlspecialchars($checkout); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Reserva</button>
        <a href="reservas.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
