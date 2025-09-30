<?php
 
include '../connection.php';
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"]) && isset($_POST["nome"]) &&  isset($_POST["numero"]) && isset($_POST["email"])) {
 
        $query = "UPDATE Contacto SET nome=?, numero=?, email=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $_POST["nome"], $_POST["numero"], $_POST["email"], $_POST["id"]);
            
            if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                header("Location: ../index.php");
                die();
            } else {
                echo "Nenhuma Contacto encontrado com o ID especificado.";
                mysqli_stmt_close($stmt);
                die();
            }
            } else {
            echo "Erro: " . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            }
        } else {
            echo "Erro na preparação da consulta: " . mysqli_error($conn);
        }
 
    } else {
        echo "ERRO - Dados inválidos";
        die();
    }
} else {
    echo "ERRO";
    die();
}