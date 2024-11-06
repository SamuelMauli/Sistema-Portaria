<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

include('../includes/db.php');

// Validação dos dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se todos os dados necessários estão presentes
    if (!isset($_POST['visitante_id'], $_POST['data'], $_POST['hora_inicio'], $_POST['hora_fim'], $_POST['finalidade_id'], $_POST['responsavel_aeb_id'], $_POST['sala_id'], $_POST['quantidade_pessoas'])) {
        echo "Dados incompletos. Por favor, preencha todos os campos obrigatórios.";
        exit();
    }

    $visitante_id = $_POST['visitante_id'];
    $data = $_POST['data'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $finalidade_id = $_POST['finalidade_id'];
    $responsavel_aeb_id = $_POST['responsavel_aeb_id'];
    $observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : null;
    $sala_id = $_POST['sala_id'];
    $quantidade_pessoas = $_POST['quantidade_pessoas'];
    $bebidas = isset($_POST['bebidas']) ? 1 : 0; // Se marcado, valor é 1, senão 0

    // Prevenir SQL Injection com prepared statements
    $stmt = $conn->prepare("INSERT INTO reunioes 
        (visitante_id, data_visita, hora_visita, hora_visita_saida, finalidade_id, responsavel_aeb_id, observacoes, sala_aeb_id, Qnt_pessoas, Bebidas) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Verificar se o prepare foi bem-sucedido
    if ($stmt === false) {
        echo "Erro na preparação da consulta: " . $conn->error;
        exit();
    }

    // Bind dos parâmetros
    $stmt->bind_param("isssiisiii", $visitante_id, $data, $hora_inicio, $hora_fim, $finalidade_id, $responsavel_aeb_id, $observacoes, $sala_id, $quantidade_pessoas, $bebidas);

    // Executar a consulta
    if ($stmt->execute()) {
        echo "Reunião agendada com sucesso!";
        header('Location: ../pages/dashboard.php');
        exit();  // Evitar a execução do código abaixo após o redirecionamento
    } else {
        echo "Erro ao agendar reunião: " . $stmt->error;
    }

    // Fechar o statement
    $stmt->close();
} else {
    echo "Método inválido. Somente POST é permitido.";
}
?>
