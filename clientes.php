<?php
session_start();
include 'connection.php';

// Verifica se o funcionário está logado
if (!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Hotel Sol&Mar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body style="font-family: 'Poppins', sans-serif; background: #f0f4f8;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Hotel Sol&Mar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="clientes.php">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="reservas.php">Reservas</a></li>
        <li class="nav-item">
          <span class="nav-link text-white">
            👤 <?php echo htmlspecialchars($_SESSION["nome"]); ?> (<?php echo htmlspecialchars($_SESSION["cargo"]); ?>)
          </span>
        </li>
        <li class="nav-item">
          <a class="btn btn-danger btn-sm ms-2" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Sair
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Clientes</h2>
        <a href="createform.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Adicionar Cliente
        </a>
    </div>

    <!-- Aqui o crud/read.php vai gerar a tabela completa -->
    <?php include 'crud/read.php'; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
