<?php
session_start();
require_once('../includes/db.php');

$nome = $_POST['nome'];
$documento = $_POST['documento'];

// Insere a entrada no banco de dados
$stmt = $conn->prepare("INSERT INTO entradas (nome, documento, data_hora) VALUES (?, ?, NOW())");
$stmt->bind_param("ss", $nome, $documento);

if ($stmt->execute()) {
    echo "Entrada registrada com sucesso!";
} else {
    echo "Erro ao registrar entrada.";
}
?>
