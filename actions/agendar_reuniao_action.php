<?php
session_start();

$host = 'localhost'; 
$dbname = 'portaria_db'; 
$username = 'samuel'; 
$password = ''; 


// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once('../includes/db.php');

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se todos os campos obrigatórios estão presentes
    $required_fields = ['visitante_id', 'data', 'hora_inicio', 'hora_fim', 'finalidade_id', 'responsavel_aeb_id', 'sala_id', 'quantidade_pessoas'];
    $missing_fields = [];  // Para armazenar os campos que não foram preenchidos

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;  // Adiciona o nome do campo à lista dos ausentes
        }
    }

    if (!empty($missing_fields)) {
        // Se houver campos ausentes, crie uma mensagem de erro detalhada
        $missing_fields_list = implode(', ', $missing_fields);  // Junta os campos em uma string
        $_SESSION['error'] = "Os seguintes campos obrigatórios não foram preenchidos: $missing_fields_list.";
        header('Location: ../pages/agendar.php');
        exit();
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
        // 1. Verificar se a sala está disponível (não ocupada)
        $query_sala = "SELECT capacidade, ocupada FROM salas WHERE id = ?";
        $stmt_sala = $conn->prepare($query_sala);
        $stmt_sala->bind_param("i", $sala_id);
        $stmt_sala->execute();
        $result_sala = $stmt_sala->get_result();
        $sala = $result_sala->fetch_assoc();
        
        if (!$sala) {
            throw new Exception("Sala não encontrada.");
        }

        // Verificar se a sala já está ocupada
        if ($sala['ocupada'] == 1) {
            $_SESSION['error'] = "A sala está ocupada nesse horário.";
            header('Location: ../pages/agendar.php');
            exit();
        }

        // Verificar a capacidade da sala
        if ($quantidade_pessoas > $sala['capacidade']) {
            $_SESSION['error'] = "A quantidade de pessoas não pode exceder a capacidade da sala.";
            header('Location: ../pages/agendar.php');
            exit();
        }

        // 2. Verificar se não há sobreposição de horário com outra reunião (respeitando 5 minutos de diferença)
        $query_reunioes = "SELECT * FROM reunioes WHERE sala_aeb_id = ? AND data_visita = ? AND (
            (hora_visita BETWEEN ? AND ?) OR (hora_visita_saida BETWEEN ? AND ?)
        )";
        $stmt_reunioes = $conn->prepare($query_reunioes);
        $stmt_reunioes->bind_param("issssss", $sala_id, $data, $hora_inicio, $hora_fim, $hora_inicio, $hora_fim);
        $stmt_reunioes->execute();
        $result_reunioes = $stmt_reunioes->get_result();
        
        if ($result_reunioes->num_rows > 0) {
            $_SESSION['error'] = "Há uma reunião agendada nesse horário. Por favor, escolha outro.";
            header('Location: ../pages/agendar.php');
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO reunioes 
            (visitante_id, data_visita, hora_visita, hora_visita_saida, finalidade_id, responsavel_aeb_id, observacoes, sala_aeb_id, Qnt_pessoas, Bebidas) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($observacoes === null) {
            $observacoes = '';  // Se for null, passamos como string vazia
        }

        $stmt->bind_param("isssiisiii", $visitante_id, $data, $hora_inicio, $hora_fim, $finalidade_id, $responsavel_aeb_id, $observacoes, $sala_id, $quantidade_pessoas, $bebidas);

        if ($stmt->execute()) {
            // Atualizar o status da sala para ocupada
            $stmt_sala_ocupada = $conn->prepare("UPDATE salas SET ocupada = 1 WHERE id = ?");
            $stmt_sala_ocupada->bind_param("i", $sala_id);
            $stmt_sala_ocupada->execute();

            $_SESSION['success'] = "Reunião agendada com sucesso!";
        } else {
            throw new Exception("Erro ao agendar reunião: " . $stmt->error);
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    } finally {
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
        header('Location: ../pages/dashboard.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Método inválido. Somente POST é permitido.";
    header('Location: ../pages/agendar.php');
    exit();
}
?>
