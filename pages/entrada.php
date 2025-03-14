<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/db.php');

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Buscar transportadoras e finalidades do banco de dados
$transportadoras = [];
$finalidades = [];

$sql_transportadoras = "SELECT id, nome FROM transportadoras";
$result_transportadoras = $conn->query($sql_transportadoras);
if ($result_transportadoras->num_rows > 0) {
    while ($row = $result_transportadoras->fetch_assoc()) {
        $transportadoras[] = $row;
    }
}

$sql_motoristas = "SELECT id, nome FROM motoristas";
$result_motoristas = $conn->query($sql_motoristas);
if ($result_motoristas->num_rows > 0) {
    while ($row = $result_motoristas->fetch_assoc()) {
        $motoristas[] = $row;
    }
}

$sql_funcionarios = "SELECT id, nome FROM funcionarios";
$result_funcionarios = $conn->query($sql_funcionarios);
if ($result_funcionarios->num_rows > 0) {
    while ($row = $result_funcionarios->fetch_assoc()) {
        $funcionarios[] = $row;
    }
}

$sql_finalidades = "SELECT id, descricao FROM finalidades";
$result_finalidades = $conn->query($sql_finalidades);
if ($result_finalidades->num_rows > 0) {
    while ($row = $result_finalidades->fetch_assoc()) {
        $finalidades[] = $row;
    }
}


if (!$conn) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $motorista_id = !empty($_POST['motorista_id']) ? sanitize_input($_POST['motorista_id']) : null;
    $transportadora_id = !empty($_POST['transportadora_id']) ? sanitize_input($_POST['transportadora_id']) : null;
    $placa_veiculo = !empty($_POST['placa_veiculo']) ? sanitize_input($_POST['placa_veiculo']) : null;
    $finalidade_id = !empty($_POST['finalidade_id']) ? sanitize_input($_POST['finalidade_id']) : null;
    $ajudante_nome = !empty($_POST['ajudante_nome']) ? sanitize_input($_POST['ajudante_nome']) : null;
    $ajudante_doc = !empty($_POST['ajudante_doc']) ? sanitize_input($_POST['ajudante_doc']) : null;
    $funcionarios_id = !empty($_POST['funcionarios_id']) ? sanitize_input($_POST['funcionarios_id']) : null;
    $observacao = !empty($_POST['observacao']) ? sanitize_input($_POST['observacao']) : null;
    $local_entrada_id = !empty($_POST['local_entrada_id']) ? sanitize_input($_POST['local_entrada_id']) : null;

    if (empty($motorista_id) || empty($transportadora_id) || empty($placa_veiculo) || empty($finalidade_id) || empty($funcionarios_id)) {
        $error_message = "Todos os campos obrigatórios devem ser preenchidos!";
    } else {
        $data_entrada = date('Y-m-d H:i:s');
        $local_entrada_id = 1;

        // Incluindo transportadora_id na consulta SQL
        $stmt = $conn->prepare("INSERT INTO entradas_saidas 
            (motorista_id, finalidade_id, local_entrada_id, responsavel_aeb_id, data_entrada, placa_veiculo, observacao, ajudante_nome, ajudante_doc, transportadora_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Alterando bind_param para incluir transportadora_id
        $stmt->bind_param("iiisssssss", $motorista_id, $finalidade_id, $local_entrada_id, $funcionarios_id, $data_entrada, $placa_veiculo, $observacao, $ajudante_nome, $ajudante_doc, $transportadora_id);
        
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            background-color: #fff;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
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
            background-color: #3498db;
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
                grid-column: span 1; /* Botão ocupa toda a largura */
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php include('../includes/sidebar.php'); ?>
    <div class="content">
        <h2>Registrar Entrada</h2>
        <?php if (isset($success_message)) echo "<div class='message success'>$success_message</div>"; ?>
        <?php if (isset($error_message)) echo "<div class='message error'>$error_message</div>"; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="input-group">
                <label for="motorista_id">Motorista:</label>
                <select id="motorista_id" name="motorista_id" required>
                    <option value="">Selecione um motorista</option>
                    <?php foreach ($motoristas as $motorista): ?>
                        <option value="<?= $motorista['id'] ?>"><?= $motorista['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="transportadora_id">Transportadora:</label>
                <select id="transportadora_id" name="transportadora_id" required>
                    <option value="">Selecione uma Transportadora</option>
                    <?php foreach ($transportadoras as $transportadora): ?>
                        <option value="<?= $transportadora['id'] ?>"><?= $transportadora['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="placa_veiculo">Placa Veículo:</label>
                <input type="text" id="placa_veiculo" name="placa_veiculo" required>
            </div>
            <div class="input-group">
                <label for="finalidade_id">Finalidade:</label>
                <select id="finalidade_id" name="finalidade_id" required>
                    <option value="">Selecione uma Finalidade</option>
                    <?php foreach ($finalidades as $finalidade): ?>
                        <option value="<?= $finalidade['id'] ?>"><?= $finalidade['descricao'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="ajudante_nome">Nome do Ajudante:</label>
                <input type="text" id="ajudante_nome" name="ajudante_nome">
            </div>
            <div class="input-group">
                <label for="ajudante_doc">Documento do Ajudante:</label>
                <input type="text" id="ajudante_doc" name="ajudante_doc">
            </div>
            <div class="input-group">
                <label for="funcionarios_id">Responsável AEB:</label>
                <select id="funcionarios_id" name="funcionarios_id" required>
                    <option value="">Selecione um Responsavel AEB</option>
                    <?php foreach ($funcionarios as $funcionarios): ?>
                        <option value="<?= $funcionarios['id'] ?>"><?= $funcionarios['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="observacao">Observação:</label>
                <input type="text" id="observacao" name="observacao">
            </div>
            <div class="input-group">
                <label for="local_entrada_id">Local de Entrada:</label>
                <select id="local_entrada_id" name="local_entrada_id" required>
                    <option value="">Selecione um Local</option>
                    <?php
                    // Buscar locais de entrada do banco de dados
                    $sql_locais = "SELECT id, nome FROM local_entrada";
                    $result_locais = $conn->query($sql_locais);
                    if ($result_locais->num_rows > 0) {
                        while ($row = $result_locais->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Registrar Entrada</button>
        </form>
    </div>
</div>
<script>
    // $(document).ready(function() {
    //     function buscarDados(idCampo, url, campoDestino) {
    //         $(idCampo).on("input", function() {
    //             let id = $(this).val();
    //             if (id !== "") {
    //                 $.ajax({
    //                     url: url,
    //                     method: "POST",
    //                     data: { id: id },
    //                     dataType: "json",
    //                     success: function(response) {
    //                         $(campoDestino).val(response.nome || response.descricao || "");
    //                     },
    //                     error: function() {
    //                         console.error("Erro ao buscar dados.");
    //                     }
    //                 });
    //             }
    //         });
    //     }
        
    //     buscarDados("#motorista_id", "buscar_motorista.php", "#motorista_nome");
    //     buscarDados("#transportadora_id", "buscar_transportadora.php", "#transportadora_nome");
    //     buscarDados("#finalidade_id", "buscar_finalidade.php", "#finalidade_descricao");
    // });
</script>
</body>
</html>
