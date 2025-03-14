<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se todos os campos obrigatórios estão presentes
    $required_fields = ['visitante_id', 'data', 'hora_inicio', 'hora_fim', 'finalidade_id', 'responsavel_aeb_id', 'sala_id', 'quantidade_pessoas'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error'] = "Todos os campos obrigatórios devem ser preenchidos.";
            header('Location: ../pages/agendar.php');
            exit();
        }
    }

    // Coletar dados do formulário
    $visitante_id = intval($_POST['visitante_id']);
    $data = $_POST['data'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $finalidade_id = intval($_POST['finalidade_id']);
    $responsavel_aeb_id = intval($_POST['responsavel_aeb_id']);
    $observacoes = isset($_POST['observacoes']) ? trim($_POST['observacoes']) : null;
    $sala_id = intval($_POST['sala_id']);
    $quantidade_pessoas = intval($_POST['quantidade_pessoas']);
    $bebidas = isset($_POST['bebidas']) ? 1 : 0;

    try {
        // Inserir no banco de dados
        $stmt = $conn->prepare("INSERT INTO reunioes 
            (visitante_id, data_visita, hora_visita, hora_visita_saida, finalidade_id, responsavel_aeb_id, observacoes, sala_aeb_id, Qnt_pessoas, Bebidas) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssiisiii", $visitante_id, $data, $hora_inicio, $hora_fim, $finalidade_id, $responsavel_aeb_id, $observacoes, $sala_id, $quantidade_pessoas, $bebidas);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Reunião agendada com sucesso!";
        } else {
            throw new Exception("Erro ao agendar reunião: " . $stmt->error);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
        header('Location: ../pages/dashboard.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Método inválido. Somente POST é permitido.";
    header('Location: ../pages/agendar.php');
    exit();
}
?>
