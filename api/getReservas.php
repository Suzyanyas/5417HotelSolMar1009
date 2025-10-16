<?php
require "../connection.php";
header('Content-Type: application/json; charset=utf-8');

// Consulta todas as reservas
$sql = "SELECT * FROM reservas";
$result = $conn->query($sql);

$reservas = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }
}

// Retorna sempre um JSON
echo json_encode($reservas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$conn->close();
?>
