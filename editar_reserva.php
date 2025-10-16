<?php
session_start();
include "connection.php";

// Verifica se o funcionário está logado
if (!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

// Verifica se o ID foi enviado
if (!isset($_GET['id'])) {
    header("Location: reservas.php");
    exit();
}

$id = intval($_GET['id']);

// Busca os dados da reserva
$query = "SELECT * FROM reservas WHERE id = $id";
$result = mysqli_query($conn, $query);
$reserva = mysqli_fetch_assoc($result);

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = mysqli_real_escape_string($conn, $_POST['cliente']);
    $quarto = mysqli_real_escape_string($conn, $_POST['quarto']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);

    $update = "UPDATE reservas SET 
                cliente='$cliente', 
                quarto='$quarto', 
                checkin='$checkin', 
                checkout='$checkout', 
                estado='$estado'
               WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        header("Location: reservas.php?msg=Reserva+atualizada+com+sucesso");
        exit();
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Reserva</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Cliente</label>
            <input type="text" name="cliente" class="form-control" value="<?php echo htmlspecialchars($reserva['cliente']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Quarto</label>
            <input type="text" name="quarto" class="form-control" value="<?php echo htmlspecialchars($reserva['quarto']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Check-in</label>
            <input type="date" name="checkin" class="form-control" value="<?php echo $reserva['checkin']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Check-out</label>
            <input type="date" name="checkout" class="form-control" value="<?php echo $reserva['checkout']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="Pendente" <?php if($reserva['estado']=="Pendente") echo "selected"; ?>>Pendente</option>
                <option value="Confirmada" <?php if($reserva['estado']=="Confirmada") echo "selected"; ?>>Confirmada</option>
                <option value="Cancelada" <?php if($reserva['estado']=="Cancelada") echo "selected"; ?>>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="reservas.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
