<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

include('../includes/db.php');

// Obter os dados do formul�rio
$visitante_id = $_POST['visitante_id'];
$data = $_POST['data'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];
$finalidade_id = $_POST['finalidade_id'];
$responsavel_aeb_id = $_POST['responsavel_aeb_id'];
$observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : null;
$sala_id = $_POST['sala_id'];
$quantidade_pessoas = $_POST['quantidade_pessoas'];
$bebidas = isset($_POST['bebidas']) ? 1 : 0; // Se marcado, valor � 1, sen�o 0

$stmt = $conn->prepare("INSERT INTO reunioes 
    (visitante_id, data_visita, hora_visita, hora_visita_saida, finalidade_id, responsavel_aeb_id, observacoes, sala_aeb_id, Qnt_pessoas, Bebidas) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssiisiii", $visitante_id, $data, $hora_inicio, $hora_fim, $finalidade_id, $responsavel_aeb_id, $observacoes, $sala_id, $quantidade_pessoas, $bebidas);

if ($stmt->execute()) {
    echo "Reuni�o agendada com sucesso!";
    header('Location: ../pages/dashboard.php');
} else {
    echo "Erro ao agendar reuni�o: " . $stmt->error;
}

$stmt->close();
?>
