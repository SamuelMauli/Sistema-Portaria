<<<<<<< HEAD
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db.php');

// Fun√ß√£o para sanitizar dados de entrada
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar e validar as entradas
    $motorista_id = isset($_POST['motorista_id']) ? sanitize_input($_POST['motorista_id']) : null;
    $transportadora_id = isset($_POST['transportadora_id']) ? sanitize_input($_POST['transportadora_id']) : null;
    $placa_veiculo = isset($_POST['placa_veiculo']) ? sanitize_input($_POST['placa_veiculo']) : null;
    $finalidade_id = isset($_POST['finalidade_id']) ? sanitize_input($_POST['finalidade_id']) : null;
    $ajudante_nome = isset($_POST['ajudante_nome']) ? sanitize_input($_POST['ajudante_nome']) : null;
    $ajudante_doc = isset($_POST['ajudante_doc']) ? sanitize_input($_POST['ajudante_doc']) : null;
    $responsavel_id = isset($_POST['responsavel_id']) ? sanitize_input($_POST['responsavel_id']) : null;
    $observacao = isset($_POST['observacao']) ? sanitize_input($_POST['observacao']) : null;

    // Validar os campos obrigat√≥rios
    if (!$motorista_id || !$placa_veiculo || !$finalidade_id || !$responsavel_id) {
        $error_message = "Todos os campos obrigat√≥rios devem ser preenchidos!";
    } else {
        // Pega a data e hora atual
        $data_entrada = date('Y-m-d H:i:s');
        $data_saida = null; // A data de sa√≠da ser√° registrada posteriormente

        // Preparar a consulta SQL para inser√ß√£o
        $stmt = $conn->prepare("INSERT INTO entradas_saidas 
            (motorista_id, finalidade_id, local_entrada_id, responsavel_aeb_id, data_entrada, data_saida, placa_veiculo, observacao, ajudante_nome, ajudante_doc) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Definir 'local_entrada_id' fixo como exemplo, deve ser alterado conforme necess√°rio
        $local_entrada_id = 1;

        // Vincular os par√¢metros para a consulta
        $stmt->bind_param("iiisssssss", $motorista_id, $finalidade_id, $local_entrada_id, $responsavel_id, $data_entrada, $data_saida, $placa_veiculo, $observacao, $ajudante_nome, $ajudante_doc);

        if ($stmt->execute()) {
            $success_message = "Entrada registrada com sucesso!";
        } else {
            $error_message = "Erro ao registrar entrada: " . $stmt->error;
        }

        $stmt->close();
    }
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
        /* Reset b√°sico */
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

        /* Conte√∫do principal */
        .content {
            padding: 30px;
            background-color: #fff;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .content h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Formul√°rio estilizado */
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

        /* Bot√£o estilizado */
        button {
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            grid-column: span 2; /* O bot√£o vai ocupar duas colunas no grid */
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        /* Mensagens de erro e sucesso */
        .message {
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Ajustar o layout para telas menores */
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr; /* Todos os elementos em uma coluna */
            }

            button {
                grid-column: span 1; /* Bot√£o ocupa toda a largura */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <?php include('../includes/sidebar.php'); ?>

    <!-- Conte√∫do principal -->
    <div class="content">
        <h2>Registrar Entrada</h2>

        <!-- Mensagens de sucesso ou erro -->
        <?php if (isset($success_message)): ?>
            <div class="message success"><?= $success_message ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST" action="dashboard.php">
            <!-- Motorista -->
            <div class="input-group">
                <label for="motorista_id">Motorista (ID):</label>
                <input type="number" id="motorista_id" name="motorista_id" required>
            </div>

            <!-- Transportadora -->
            <div class="input-group">
                <label for="transportadora_id">Transportadora (ID):</label>
                <input type="number" id="transportadora_id" name="transportadora_id">
            </div>

            <!-- Ve√≠culo -->
            <div class="input-group">
                <label for="placa_veiculo">Placa Ve√≠culo:</label>
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

            <!-- Respons√°vel -->
            <div class="input-group">
                <label for="responsavel_id">Respons√°vel AEB:</label>
                <input type="number" id="responsavel_id" name="responsavel_id" required>
            </div>

            <!-- Observa√ß√µes -->
            <div class="input-group">
                <label for="observacao">Observa√ß√£o:</label>
                <input type="text" id="observacao" name="observacao">
            </div>

            <!-- Bot√£o de envio -->
            <button type="submit">Registrar Entrada</button>
        </form>
    </div>
</div>

</body>
</html>
=======
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db_connect.php'); // Certifique-se de ter o arquivo de conex„o ao banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $motorista_id = $_POST['motorista_id'];
    $transportadora_id = $_POST['transportadora_id'];  // Verifique se h· necessidade de salvar esse campo tambÈm
    $placa_veiculo = $_POST['placa_veiculo'];
    $finalidade_id = $_POST['finalidade_id'];
    $ajudante_nome = $_POST['ajudante_nome'] ?? null;
    $ajudante_doc = $_POST['ajudante_doc'] ?? null;
    $responsavel_id = $_POST['responsavel_id'];
    $observacao = $_POST['observacao'] ?? null;

    // Pega a data e hora atual para registrar a entrada
    $data_entrada = date('Y-m-d H:i:s');  // Data e hora atual
    $data_saida = null;  // A data de saÌda ser· registrada posteriormente

    // Prepara a consulta SQL para inserÁ„o
    $stmt = $conn->prepare("INSERT INTO entradas_saidas 
        (motorista_id, finalidade_id, local_entrada_id, responsavel_aeb_id, data_entrada, data_saida, placa_veiculo, observacao, ajudante_nome, ajudante_doc) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Substitua 'local_entrada_id' pelo valor correto, se for fixo ou derivado de outro dado.
    $local_entrada_id = 1; // Exemplo, troque isso pelo valor correto

    // Passando os par‚metros para a query
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
        /* Reset b·sico */
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


        /* Conte˙do principal */
        .content {
            padding: 30px;
            background-color: #f9f9f9;
            width: 100%;

        }

        .content h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Formul·rio estilizado */
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

        /* Bot„o estilizado */
        button {
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            grid-column: span 2; /* O bot„o vai ocupar duas colunas no grid */
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
                grid-column: span 1; /* Bot„o ocupa toda a largura */
            }
        }
    </style>

</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <?php include('../includes/sidebar.php'); ?>

    <!-- Conte˙do principal -->
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

            <!-- VeÌculo -->
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

            <!-- Respons·vel -->
            <div class="input-group">
                <label for="responsavel_id">Respons·vel AEB:</label>
                <input type="number" id="responsavel_id" name="responsavel_id" required>
            </div>

            <!-- ObservaÁ„o -->
            <div class="input-group" style="flex: 1 1 100%;">
                <label for="observacao">ObservaÁ„o:</label>
                <input type="text" id="observacao" name="observacao">
            </div>

            <!-- Bot„o de Envio -->
            <button type="submit">Registrar Entrada</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>
>>>>>>> ccc14c2 (Initial commit)
