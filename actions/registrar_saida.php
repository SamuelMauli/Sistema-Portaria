<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once('../includes/db.php');

$conn = conectarBancoDeDados();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_saida'])) {
    $id_saida = $_POST['id_saida'];  
    $hora_saida = isset($_POST['hora_saida']) ? $_POST['hora_saida'] : null; 
    $responsavel_aeb_id = $_SESSION['user_id']; 
    if (empty($id_saida) || empty($hora_saida)) {
        $_SESSION['erro_saida'] = "Todos os campos são obrigatórios.";
        header('Location: ../pages/saida.php');
        exit();
    }

    $stmt_check_responsavel = $conn->prepare("SELECT id FROM funcionarios WHERE id = ?");
    $stmt_check_responsavel->bind_param('i', $responsavel_aeb_id);
    $stmt_check_responsavel->execute();
    $result_responsavel = $stmt_check_responsavel->get_result();

    if ($result_responsavel->num_rows == 0) {
        $_SESSION['erro_saida'] = "Responsável não encontrado na tabela de funcionários.";
        header('Location: ../pages/saida.php');
        exit();
    }

    $data_atual = date('Y-m-d');

    $data_saida = $data_atual . ' ' . $hora_saida . ':00';  

    try {
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("UPDATE entradas_saidas 
                               SET hora_saida = ?, responsavel_aeb_id = ?, data_saida = ?, user_id = ? 
                               WHERE id = ?");

        $stmt->bind_param('ssssi', $data_saida, $responsavel_aeb_id, $data_saida, $user_id, $id_saida);

        if ($stmt->execute()) {
            $_SESSION['sucesso_saida'] = "Saída registrada com sucesso!";
            header('Location: ../pages/saida.php');
            exit();
        } else {
            $_SESSION['erro_saida'] = "Erro ao registrar saída: " . $stmt->error;
            header('Location: ../pages/saida.php');
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['erro_saida'] = 'Erro: ' . $e->getMessage();
        header('Location: ../pages/saida.php');
        exit();
    }
}

// Liberar sala de visita/////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['liberar_sala'])) {
    $visita_id = $_POST['visita_id'];

    if (empty($visita_id)) {
        $_SESSION['erro_liberar'] = "Selecione uma visita para liberar a sala.";
        header('Location: ../pages/saida.php');
        exit();
    }

    $stmt = $conn->prepare("UPDATE salas SET ocupada = 0 WHERE id = ?");
    $stmt->bind_param('i', $visita_id);

    if ($stmt->execute()) {
        $_SESSION['sucesso_liberar'] = "Sala liberada com sucesso!";
        header('Location: ../pages/saida.php');
        exit();
    } else {
        $_SESSION['erro_liberar'] = "Erro ao liberar sala: " . $stmt->error;
        header('Location: ../pages/saida.php');
        exit();
    }
}
    
?>
