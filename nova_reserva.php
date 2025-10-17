<?php
session_start(); // 👈 Corrigido: iniciar sessão
if (!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

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
    } elseif (strtotime($checkout) < strtotime($checkin)) { // comparação segura
        $erro = "A data de check-out não pode ser anterior à data de check-in.";
    } else {
        // Verifica se já existe reserva ativa para o quarto
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
        }

        mysqli_stmt_close($stmt_check); // fechar statement
    }

    if (empty($erro)) {
        $query = "INSERT INTO reservas (cliente, quarto, checkin, checkout, estado, funcionario_id) 
          VALUES (?, ?, ?, ?, 'Ativa', ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sissi", $cliente, $quarto, $checkin, $checkout, $_SESSION['funcionario_id']);



        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: reservas.php");
            exit();
        } else {
            $erro = "Erro ao salvar a reserva: " . mysqli_error($conn);
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)),
                    url('imagens/background.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Navbar translúcida */
    .navbar {
        background: rgba(0, 0, 0, 0.7) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .container-form {
        background: rgba(255, 255, 255, 0.15); 
        backdrop-filter: blur(15px); 
        -webkit-backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 40px;
        max-width: 600px;
        margin: 80px auto;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        color: #fff;
    }

    h2 {
        text-align: center;
        font-weight: 600;
        margin-bottom: 20px;
        color: #fff;
    }

    .form-label {
        color: #fff;
        font-weight: 500;
    }

    .form-control {
        background-color: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        border-radius: 10px;
    }

    .form-control::placeholder {
        color: #eee;
    }

    .btn-primary {
        background-color: #0078ff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        
    }

    .btn-primary:hover {
        background-color: #005fd1;
        transform: scale(1.03);
    }

    .btn-secondary {
        background-color: rgba(255,255,255,0.3);
        border: none;
        color: #fff;
        border-radius: 10px;
        transition: all 0.3s ease;
        vertical-align: middle;
        padding: 10px 20px;
    }

    .btn-secondary:hover {
        background-color: rgba(255,255,255,0.5);
        transform: scale(1.03);
    }
    .d-flex {
        gap: 15px; 
    }


    .alert {
        background: rgba(255, 0, 0, 0.2);
        border: 1px solid rgba(255, 0, 0, 0.4);
        color: #fff;
    }

    @media (max-width: 768px) {
        .container-form {
        padding: 25px;
        margin: 40px 15px;
        }
    }
</style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="imagens/logo.png" alt="Hotel Sol&Mar" width="50" height="50" class="me-2">
      Hotel Sol&Mar
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="reservas.php">Reservas</a></li>
        <li class="nav-item"><a class="nav-link active" href="nova_reserva.php">Nova Reserva</a></li>
        <li class="nav-item">
          <span class="nav-link text-white">
            👤 <?php echo htmlspecialchars($_SESSION["nome"]); ?> (<?php echo htmlspecialchars($_SESSION["cargo"]); ?>)
          </span>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="btn btn-danger ms-2">
            <i class="bi bi-box-arrow-right"></i> Sair
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-form">
    <h2 class="mb-4 text-center">Nova Reserva</h2>

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

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Reserva</button>
            <a href="reservas.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
