<?php
session_start();
include "connection.php";

// Verifica se o funcionário está logado
if (!isset($_SESSION["funcionario_id"])) {
    header("Location: login.php");
    exit();
}

// Verifica se o ID foi enviado
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Garante que seja um número inteiro

    // Deleta a reserva
    $query = "DELETE FROM reservas WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        // Redireciona de volta para a lista de reservas com sucesso
        header("Location: reservas.php?msg=Reserva+excluída+com+sucesso");
        exit();
    } else {
        echo "Erro ao excluir a reserva: " . mysqli_error($conn);
    }
} else {
    echo "ID da reserva não especificado.";
}
?>
