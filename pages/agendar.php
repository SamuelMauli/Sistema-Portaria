<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

include('../includes/db.php');

try {
    // Buscar salas disponíveis
    $query_salas = "SELECT id, nome FROM salas WHERE ocupada = 0";  
    $result_salas = $conn->query($query_salas);
    if (!$result_salas) {
        throw new Exception("Erro ao buscar salas: " . $conn->error);
    }

    // Buscar finalidades
    $query_finalidades = "SELECT id, descricao FROM finalidades";
    $result_finalidades = $conn->query($query_finalidades);
    if (!$result_finalidades) {
        throw new Exception("Erro ao buscar finalidades: " . $conn->error);
    }

    // Buscar visitantes
    $query_visitantes = "SELECT id, nome FROM visitantes";
    $result_visitantes = $conn->query($query_visitantes);
    if (!$result_visitantes) {
        throw new Exception("Erro ao buscar visitantes: " . $conn->error);
    }

    // Buscar funcionários
    $sql_funcionarios = "SELECT id, nome FROM funcionarios";
    $result_funcionarios = $conn->query($sql_funcionarios);
    if (!$result_funcionarios) {
        throw new Exception("Erro ao buscar funcionários: " . $conn->error);
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Reunião</title>
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

        /* Layout principal */
        .main-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #fff;
            margin-left: 50px;
        }

        .content h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Formulário estilizado */
        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            width: 100%;
        }

        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        .input-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            padding: 15px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            grid-column: 1 / -1; 
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

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

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .content {
                padding: 20px;
            }

            form {
                grid-template-columns: 1fr; 
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <?php include('../includes/sidebar.php'); ?>

        <!-- Conteúdo Principal -->
        <div class="content">
            <h2>Agendar Reunião</h2>

            <!-- Mensagens de Erro -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <!-- Formulário -->
            <form action="../actions/agendar_reuniao_action.php" method="post">
                <!-- Visitante -->
                <div class="input-group">
                    <label for="visitante_id">Visitante:</label>
                    <select name="visitante_id" required>
                        <option value="">Selecione o visitante</option>
                        <?php while ($visitante = $result_visitantes->fetch_assoc()): ?>
                            <option value="<?= $visitante['id']; ?>"><?= $visitante['nome']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Data e Horário -->
                <div class="input-group">
                    <label for="data">Data:</label>
                    <input type="date" name="data" required>
                </div>

                <div class="input-group">
                    <label for="hora_inicio">Hora de Início:</label>
                    <input type="time" name="hora_inicio" required>
                </div>

                <div class="input-group">
                    <label for="hora_fim">Hora de Fim:</label>
                    <input type="time" name="hora_fim" required>
                </div>

                <!-- Finalidade -->
                <div class="input-group">
                    <label for="finalidade_id">Finalidade:</label>
                    <select name="finalidade_id" required>
                        <option value="">Selecione a finalidade</option>
                        <?php while ($finalidade = $result_finalidades->fetch_assoc()): ?>
                            <option value="<?= $finalidade['id']; ?>"><?= $finalidade['descricao']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Responsável AEB -->
                <div class="input-group">
                    <label for="responsavel_aeb_id">Responsável AEB:</label>
                    <select name="responsavel_aeb_id" required>
                        <option value="">Selecione um Responsável AEB</option>
                        <?php while ($funcionario = $result_funcionarios->fetch_assoc()): ?>
                            <option value="<?= $funcionario['id']; ?>"><?= $funcionario['nome']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Observações -->
                <div class="input-group">
                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes" rows="3"></textarea>
                </div>

                <!-- Sala -->
                <div class="input-group">
                    <label for="sala_id">Sala:</label>
                    <select name="sala_id" required>
                        <option value="">Selecione a sala</option>
                        <?php while ($sala = $result_salas->fetch_assoc()): ?>
                            <option value="<?= $sala['id']; ?>"><?= $sala['nome']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Quantidade de Pessoas -->
                <div class="input-group">
                    <label for="quantidade_pessoas">Quantidade de Pessoas:</label>
                    <input type="number" name="quantidade_pessoas" required>
                </div>

                <!-- Bebidas -->
                <div class="input-group">
                    <label for="bebidas">Necessário Bebidas?</label>
                    <input type="checkbox" name="bebidas" value="1">
                </div>

                <!-- Botão de Envio -->
                <button type="submit">Agendar Reunião</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>