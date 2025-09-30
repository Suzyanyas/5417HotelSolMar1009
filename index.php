<?php
session_start();


// Se não estiver logado, redireciona
if (!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';
?>

<!DOCTYPE html> 
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-journal-bookmark"></i> Agenda
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
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
        <a href="createform.php" class="btn btn-primary mb-3">
            <i class="bi bi-plus"></i> Adicionar Contacto
        </a>
    </div>

    <?php include 'crud/read.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



