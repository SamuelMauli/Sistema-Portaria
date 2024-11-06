<?php
global $conn;
session_start();
require_once('../includes/db.php');

$data = $_POST['data'];
$hora = $_POST['hora'];
$assunto = $_POST['assunto'];

$stmt = $conn->prepare("INSERT INTO reunioes (data, hora, assunto) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $data, $hora, $assunto);

if ($stmt->execute()) {
    echo "Reunião agendada com sucesso!";
} else {
    echo "Erro ao agendar reunião.";
}
?>
