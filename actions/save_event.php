<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $titulo = $data['title'];
    $descricao = $data['description'];
    $data_inicio = $data['start'];
    $data_fim = $data['end'];

    $stmt = $conn->prepare("INSERT INTO eventos_calendario (titulo, descricao, data_inicio, data_fim) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $descricao, $data_inicio, $data_fim);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}
?>