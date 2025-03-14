<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../includes/db.php');

$conn = conectarBancoDeDados();

$entradas_sem_saida = $conn->query("
    SELECT es.id, m.nome AS motorista_nome 
    FROM entradas_saidas es
    JOIN motoristas m ON es.motorista_id = m.id
    WHERE es.data_saida IS NULL
");

$visitas_ocupadas = $conn->query("
    SELECT s.id, s.nome AS sala_nome 
    FROM salas s 
    WHERE s.ocupada = 1
");


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Saída - Sistema de Portaria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .container-saidas {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 0 20px;
            flex-wrap: wrap;
        }
        .form-container {
            background-color: white;
            width: 80%;
            max-width: 400px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 50px;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include('../includes/sidebar.php'); ?>

        <main class="container-saidas">
            <!-- Formulário para registrar saída de entrada -->
            <div class="form-container">
                <h2>Registrar Saída de Entrada</h2>
                <form action="../actions/registrar_saida.php" method="post">
                    <div class="input-group">
                        <label for="id_saida">Selecione a entrada sem saída registrada:</label>
                        <select name="id_saida" id="id_saida" required>
                            <option value="">Selecione uma entrada</option>
                            <?php while ($row = $entradas_sem_saida->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>">Motorista: <?= $row['motorista_nome'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="hora_saida">Hora de Saída:</label>
                        <input type="time" name="hora_saida" id="hora_saida" required>
                    </div>
                    <button type="submit" name="registrar_saida">Registrar Saída</button>
                    <?php if (isset($_SESSION['erro_saida'])): ?>
                        <div class="error-message"><?= $_SESSION['erro_saida'] ?></div>
                        <?php unset($_SESSION['erro_saida']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['sucesso_saida'])): ?>
                        <div class="success-message"><?= $_SESSION['sucesso_saida'] ?></div>
                        <?php unset($_SESSION['sucesso_saida']); ?>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Formulário para liberar sala de visitas -->
            <div class="form-container">
                <h2>Liberar Sala de Visitas</h2>
                <form action="../actions/registrar_saida.php" method="post">
                    <div class="input-group">
                        <label for="visita_id">Selecione a visita para liberar a sala:</label>
                        <select name="visita_id" id="visita_id" required>
                            <option value="">Selecione uma visita</option>
                            <?php while ($row = $visitas_ocupadas->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>">Sala: <?= $row['sala_nome'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="liberar_sala">Liberar Sala</button>
                    <?php if (isset($_SESSION['erro_liberar'])): ?>
                        <div class="error-message"><?= $_SESSION['erro_liberar'] ?></div>
                        <?php unset($_SESSION['erro_liberar']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['sucesso_liberar'])): ?>
                        <div class="success-message"><?= $_SESSION['sucesso_liberar'] ?></div>
                        <?php unset($_SESSION['sucesso_liberar']); ?>
                    <?php endif; ?>
                </form>
            </div>

        </main>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
