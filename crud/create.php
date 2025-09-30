<?php
 
include '../connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome"]) && isset($_POST["numero"]) && isset($_POST["email"])) {
        $query = "INSERT INTO Contacto (nome, numero, email) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $_POST["nome"], $_POST["numero"], $_POST["email"]);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: ../index.php");
                die();
            } else {
                echo "Erro: " . mysqli_error($conn);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "Erro na preparação da query: " . mysqli_error($conn);
        }
    } else {
        echo "ERRO - Por favor preencha todos os campos";
    }
} else {
    //Exibe Fomrmulário
}