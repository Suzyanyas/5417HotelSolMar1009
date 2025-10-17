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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)),
                        url('imagens/background.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
        }

        /* Navbar translúcida */
        .navbar {
            background: rgba(0, 0, 0, 0.7) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Container com efeito vidro */
        .container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            padding: 30px;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.03);
        }

        .table {
            color: #fff;
        }

        .table th {
            background-color: rgba(0,0,0,0.5) !important;
            color: #fff;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(255,255,255,0.05);
        }

        .table-striped > tbody > tr:nth-of-type(even) {
            background-color: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            font-weight: 600;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="imagens/logo.png" alt="Hotel Sol&Mar" width="50" height="50" class="me-2">
      <span>Hotel Sol&Mar</span>
    </a>

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

<!-- Conteúdo -->
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
