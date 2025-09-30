<?php
session_start();

// Verifica se o funcionário está logado
if (!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

include "connection.php";

// Consulta todas as reservas
$query = "SELECT * FROM reservas ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Hotel Sol&Mar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    .carousel-item img {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background: #e9e7e7ff;
    }
    .navbar, #carouselExample{
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    }
</style>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="reservas.php">
      <img src="imagens/logo.png" alt="Hotel Sol&Mar" width="80" height="80" class="me-2">
      <span>Hotel Sol&Mar</span>
    </a>

    <!-- Botão hamburguer (aparece no mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links da navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto d-flex align-items-center">
        <!-- Nome do funcionário logado -->
        <li class="nav-item me-2">
          <span class="nav-link text-white">
            👤 <?php echo htmlspecialchars($_SESSION["nome"]); ?> (<?php echo htmlspecialchars($_SESSION["cargo"]); ?>)
          </span>
        </li>
        <!-- Botão Nova Reserva -->
        <li class="nav-item me-2">
          <a href="nova_reserva.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nova Reserva
          </a>
        </li>
        <!-- Botão Logout -->
        <li class="nav-item">
          <a href="logout.php" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Sair
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2>Lista de Reservas</h2>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Quarto</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Estado</th>
            </tr>
            
        </thead>

        
        <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".htmlspecialchars($row['cliente'])."</td>";
                    echo "<td>".$row['quarto']."</td>";
                    echo "<td>".$row['checkin']."</td>";
                    echo "<td>".$row['checkout']."</td>";
                    echo "<td>".$row['estado']."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Nenhuma reserva encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
