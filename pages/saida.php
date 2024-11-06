<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../includes/db.php');

// Conexão com o banco de dados
$conn = getConnection();

// Consulta entradas sem saída registrada
$entradas_sem_saida = $conn->query("SELECT id, motorista_id FROM entradas_saidas WHERE data_saida IS NULL");

// Consulta visitas com salas ocupadas
$visitas_ocupadas = $conn->query("SELECT av.id, av.sala_aeb_id, s.nome AS sala_nome 
                                  FROM agenda_visitas av 
                                  JOIN sala_aeb s ON av.sala_aeb_id = s.id 
                                  WHERE av.hora_visita_saida IS NULL");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Saída - Sistema de Portaria</title>

    <!-- Conectando o CSS principal -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Estilo adicional para a página -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            background-color: white;
            width: 100%;
            max-width: 800px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }
        select, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        .error-message {
            color: #e74c3c;
            font-size: 14px;
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
                    <label for="entrada_id">Selecione a entrada sem saída registrada:</label>
                    <select name="entrada_id" id="entrada_id" required>
                        <option value="">Selecione uma entrada</option>
                        <?php while ($row = $entradas_sem_saida->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                Entrada de Motorista ID: <?php echo htmlspecialchars($row['motorista_id']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Registrar Saída</button>
                <?php if (isset($_SESSION['erro_saida'])): ?>
                    <div class="error-message">
                        <?php echo $_SESSION['erro_saida']; ?>
                    </div>
                    <?php unset($_SESSION['erro_saida']); ?>
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
                            <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                Sala: <?php echo htmlspecialchars($row['sala_nome']); ?> (Visita ID: <?php echo htmlspecialchars($row['id']); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Liberar Sala</button>
                <?php if (isset($_SESSION['erro_visita'])): ?>
                    <div class="error-message">
                        <?php echo $_SESSION['erro_visita']; ?>
                    </div>
                    <?php unset($_SESSION['erro_visita']); ?>
                <?php endif; ?>
            </form>
        </div>
    </main>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>
