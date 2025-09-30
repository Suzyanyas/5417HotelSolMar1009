<?php
    include 'connection.php';
 
    $data = NULL;
    if( $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) ){
 
        $query = "SELECT * FROM Contacto WHERE id =".$_GET["id"].";";
        
        $result = mysqli_query($conn,$query);
 
        if($result){
 
            $data = mysqli_fetch_assoc($result);
 
        }else{
            echo "Erro - Aconteceu algo inesperado na base de dados!";
            die();
        }
 
    }
    else{
        echo "Erro - Aconteceu algo inesperado!";
        die();
    }
 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person-plus"></i> Actualizar Contacto</h4>
                    </div>
                    <div class="card-body">
                        <form action="crud/update.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $data["id"];?>">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input name="nome" type="text" class="form-control" id="nome" placeholder="Nome" required
                                value="<?php echo $data["nome"];?>"
                                >
                            </div>
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input name="numero" type="number" class="form-control" id="numero" placeholder="Número" required
                                value="<?php echo $data["numero"];?>"
                                >
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" id="email" placeholder="Email" required
                                value="<?php echo $data["email"];?>"
                                >
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Actualizar
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>