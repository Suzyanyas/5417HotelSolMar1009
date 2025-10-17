<?php
session_start();
if (!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

// Inicializa variáveis
$total_clientes = 0;
$total_reservas = 0;

// Consulta total de clientes
// Consulta total de clientes (contactos)
$result_clientes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM contacto");
if ($result_clientes) {
    $total_clientes = mysqli_fetch_assoc($result_clientes)['total'];
}


// Consulta total de reservas
$result_reservas = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reservas");
if ($result_reservas) {
    $total_reservas = mysqli_fetch_assoc($result_reservas)['total'];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hotel Sol&Mar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<style>
body {
    font-family: 'Poppins', sans-serif;
    /* Imagem de fundo */
    background: url('imagens/background2.png') no-repeat center center fixed;
    background-size: cover; /* Preenche a tela mantendo proporção */
    min-height: 100vh;
}

/* Ajustes para telas muito pequenas (ex: celulares) */
@media (max-width: 368px) {
    body {
        background-position: top center; /* Ajusta posição para ver parte importante da imagem */
        background-attachment: scroll;   /* Evita problemas em alguns celulares */
    }
}
</style>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="imagens/logo.png" alt="Hotel Sol&Mar" width="50" height="50" class="me-2">
      <span>Hotel Sol&Mar</span>
    </a>

    <!-- Botão hamburguer (mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links da navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
        <li class="nav-item"><a class="nav-link active" href="reservas.php">Reservas</a></li>
        <li class="nav-item"><a class="nav-link" href="nova_reserva.php">Nova Reserva</a></li>
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

<div class="container mt-4">

    <!-- Cards do Dashboard -->
    <div class="row g-4 d-flex align-items-stretch">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text display-6"><?php echo $total_clientes; ?></p>
                    <a href="clientes.php" class="btn btn-light btn-sm">Ver Clientes</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Reservas</h5>
                    <p class="card-text display-6"><?php echo $total_reservas; ?></p>
                    <a href="reservas.php" class="btn btn-light btn-sm">Ver Reservas</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Ações Rápidas</h5>
                    <a href="createform.php" class="btn btn-light btn-sm d-block mx-auto mb-2">
                        <i class="bi bi-plus-circle"></i> Adicionar Cliente
                    </a>
                    <br>
                    <a href="nova_reserva.php" class="btn btn-light btn-sm d-block mx-auto">
                        <i class="bi bi-calendar-plus"></i> Nova Reserva
                    </a>
                </div>
            </div>
        </div>
    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
