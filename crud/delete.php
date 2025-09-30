<?php
 
include "../connection.php";
 
if( $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])){
 
    $query = "DELETE FROM Contacto WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);
        
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                header("Location: ../index.php");
                exit();
            } else {
                echo "Nenhuma contacto encontrado com o ID especificado.";
            }
        } else {
            echo "Erro: " . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Erro na preparação da query: " . mysqli_error($conn);
    }
    echo "<br>";
 
}else{
    echo "<h1>Operação falhou</h1>";
}
 
?>