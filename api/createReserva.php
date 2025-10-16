<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require "../connection.php";

// Só aceitar POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido, use POST"]);
    exit;
}

// Lê o corpo JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verifica campos obrigatórios
if (!isset($data["cliente"]) || !isset($data["quarto"]) || !isset($data["checkin"]) || !isset($data["checkout"])) {
    http_response_code(400);
    echo json_encode(["erro" => "Campos obrigatórios: cliente, quarto, checkin, checkout"]);
    exit;
}

$cliente = $data["cliente"];
$quarto = $data["quarto"];
$checkin = $data["checkin"];
$checkout = $data["checkout"];
$estado = isset($data["estado"]) ? $data["estado"] : "Ativa"; // default "Ativa"

$stmt = $conn->prepare("INSERT INTO reservas (cliente, quarto, checkin, checkout, estado) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $cliente, $quarto, $checkin, $checkout, $estado);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        "sucesso" => "Reserva criada com sucesso!",
        "id" => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao criar reserva: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
