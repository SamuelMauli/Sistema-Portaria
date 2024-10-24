<?php
session_start();
require_once('../includes/db.php');

$data = $_POST['data'];
$hora = $_POST['hora'];
$assunto = $_POST['assunto'];

// Insere a reuni�o no banco de dados
$stmt = $conn->prepare("INSERT INTO reunioes (data, hora, assunto) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $data, $hora, $assunto);

if ($stmt->execute()) {
    echo "Reuni�o agendada com sucesso!";
} else {
    echo "Erro ao agendar reuni�o.";
}
?>
