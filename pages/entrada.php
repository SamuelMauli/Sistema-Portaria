<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db_connect.php'); // Certifique-se de ter o arquivo de conexão ao banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $motorista_id = $_POST['motorista_id'];
    $transportadora_id = $_POST['transportadora_id'];  // Verifique se há necessidade de salvar esse campo também
    $placa_veiculo = $_POST['placa_veiculo'];
    $finalidade_id = $_POST['finalidade_id'];
    $ajudante_nome = $_POST['ajudante_nome'] ?? null;
    $ajudante_doc = $_POST['ajudante_doc'] ?? null;
    $responsavel_id = $_POST['responsavel_id'];
    $observacao = $_POST['observacao'] ?? null;

    // Pega a data e hora atual para registrar a entrada
    $data_entrada = date('Y-m-d H:i:s');  // Data e hora atual
    $data_saida = null;  // A data de saída será registrada posteriormente

    // Prepara a consulta SQL para inserção
    $stmt = $conn->prepare("INSERT INTO entradas_saidas 
        (motorista_id, finalidade_id, local_entrada_id, responsavel_aeb_id, data_entrada, data_saida, placa_veiculo, observacao, ajudante_nome, ajudante_doc) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Substitua 'local_entrada_id' pelo valor correto, se for fixo ou derivado de outro dado.
    $local_entrada_id = 1; // Exemplo, troque isso pelo valor correto

    // Passando os parâmetros para a query
    $stmt->bind_param("iiisssssss", $motorista_id, $finalidade_id, $local_entrada_id, $responsavel_id, $data_entrada, $data_saida, $placa_veiculo, $observacao, $ajudante_nome, $ajudante_doc);

    if ($stmt->execute()) {
        echo "Entrada registrada com sucesso!";
    } else {
        echo "Erro ao registrar entrada: " . $stmt->error;
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada - Sistema de Portaria</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        /* Reset básico */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }


        /* Conteúdo principal */
        .content {
            padding: 30px;
            background-color: #f9f9f9;
            width: 100%;

        }

        .content h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Formulário estilizado */
        form {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 100%;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            min-width: 300px;
        }

        .input-group label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .input-group input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            width: 100%;
        }

        .input-group input:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Botão estilizado */
        button {
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            grid-column: span 2; /* O botão vai ocupar duas colunas no grid */
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        /* Ajustar o layout para telas menores */
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr; /* Todos os elementos em uma coluna */
            }

            button {
                grid-column: span 1; /* Botão ocupa toda a largura */
            }
        }
    </style>

</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <?php include('../includes/sidebar.php'); ?>

    <!-- Conteúdo principal -->
    <div class="content">
        <h2>Registrar Entrada</h2>

        <form method="POST" action="dashboard.php">
            <!-- Motorista -->
            <div class="input-group">
                <label for="motorista_id">Motorista (ID):</label>
                <input type="number" id="motorista_id" name="motorista_id" required>
            </div>

            <!-- Transportadora -->
            <div class="input-group">
                <label for="transportadora_id">Transportadora (ID):</label>
                <input type="number" id="transportadora_id" name="transportadora_id" required>
            </div>

            <!-- Veículo -->
            <div class="input-group">
                <label for="veiculo_id">Placa Veiculo:</label>
                <input type="text" id="placa_veiculo" name="placa_veiculo" required>
            </div>

            <!-- Finalidade -->
            <div class="input-group">
                <label for="finalidade_id">Finalidade (ID):</label>
                <input type="number" id="finalidade_id" name="finalidade_id" required>
            </div>

            <!-- Ajudante -->
            <div class="input-group">
                <label for="ajudante_nome">Nome do Ajudante:</label>
                <input type="text" id="ajudante_nome" name="ajudante_nome">
            </div>

            <div class="input-group">
                <label for="ajudante_doc">Documento do Ajudante:</label>
                <input type="text" id="ajudante_doc" name="ajudante_doc">
            </div>

            <!-- Responsável -->
            <div class="input-group">
                <label for="responsavel_id">Responsável AEB:</label>
                <input type="number" id="responsavel_id" name="responsavel_id" required>
            </div>

            <!-- Observação -->
            <div class="input-group" style="flex: 1 1 100%;">
                <label for="observacao">Observação:</label>
                <input type="text" id="observacao" name="observacao">
            </div>

            <!-- Botão de Envio -->
            <button type="submit">Registrar Entrada</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>
