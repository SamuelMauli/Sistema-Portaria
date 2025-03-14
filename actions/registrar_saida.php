<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once('../includes/db.php');

// Conexão com o banco de dados
$conn = conectarBancoDeDados();

// Verifica se os dados foram enviados através do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_saida'])) {
    // Recebe os dados do formulário
    $id_saida = $_POST['id_saida'];  // ID da saída a ser registrada
    $hora_saida = isset($_POST['hora_saida']) ? $_POST['hora_saida'] : null;  // Hora de saída
    $responsavel_aeb_id = $_SESSION['user_id'];  // Responsável por dar a baixa

    // Validação dos campos
    if (empty($id_saida) || empty($hora_saida)) {
        $_SESSION['erro_saida'] = "Todos os campos são obrigatórios.";
        header('Location: ../pages/saida.php');
        exit();
    }

    // Verifica se o responsável existe na tabela funcionarios
    $stmt_check_responsavel = $conn->prepare("SELECT id FROM funcionarios WHERE id = ?");
    $stmt_check_responsavel->bind_param('i', $responsavel_aeb_id);
    $stmt_check_responsavel->execute();
    $result_responsavel = $stmt_check_responsavel->get_result();

    if ($result_responsavel->num_rows == 0) {
        $_SESSION['erro_saida'] = "Responsável não encontrado na tabela de funcionários.";
        header('Location: ../pages/saida.php');
        exit();
    }

    // Obtém a data atual no formato YYYY-MM-DD
    $data_atual = date('Y-m-d');

    // Concatena a data atual com a hora de saída
    $data_saida = $data_atual . ' ' . $hora_saida . ':00';  // Adicionando os segundos para compatibilidade com DATETIME

    try {
        // Obtém o user_id da sessão (quem fez a saída)
        $user_id = $_SESSION['user_id'];

        // Prepara a consulta para atualizar o registro de saída existente
        $stmt = $conn->prepare("UPDATE entradas_saidas 
                               SET hora_saida = ?, responsavel_aeb_id = ?, data_saida = ?, user_id = ? 
                               WHERE id = ?");

        // Associa os parâmetros com o statement
        $stmt->bind_param('ssssi', $data_saida, $responsavel_aeb_id, $data_saida, $user_id, $id_saida);

        // Executa a consulta
        if ($stmt->execute()) {
            // Se a execução for bem-sucedida
            $_SESSION['sucesso_saida'] = "Saída registrada com sucesso!";
            header('Location: ../pages/saida.php');
            exit();
        } else {
            // Se a execução falhar
            $_SESSION['erro_saida'] = "Erro ao registrar saída: " . $stmt->error;
            header('Location: ../pages/saida.php');
            exit();
        }
    } catch (Exception $e) {
        // Captura e exibe qualquer exceção que ocorra durante o processo
        $_SESSION['erro_saida'] = 'Erro: ' . $e->getMessage();
        header('Location: ../pages/saida.php');
        exit();
    }
}

// Liberar sala de visita/////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['liberar_sala'])) {
    // Recebe o ID da visita (ou da sala) para liberar
    $visita_id = $_POST['visita_id'];

    // Verifica se o ID da visita foi fornecido
    if (empty($visita_id)) {
        $_SESSION['erro_liberar'] = "Selecione uma visita para liberar a sala.";
        header('Location: ../pages/saida.php');
        exit();
    }

    // Atualiza o campo 'ocupada' para 0 para liberar a sala
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
