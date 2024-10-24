<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../includes/db.php');

$conn = getConnection();

$entradas_sem_saida = $conn->query("SELECT id, motorista_id FROM entradas_saidas WHERE data_saida IS NULL");

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
    <title>Registrar Saida - Sistema de Portaria</title>

    <!-- Conectando o CSS principal -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
    <?php include('../includes/sidebar.php'); ?>

    <!-- Conteúdo principal -->
    <main class="container-saidas">
        <div class="saida-container">
            <h2>Registrar Saida</h2>
            <!-- Formulário para registrar saída de entrada -->
            <form action="../actions/registrar_saida.php" method="post">
                <div class="input-group">
                    <label for="entrada_id">Selecione a entrada sem saida registrada:</label>
                    <select name="entrada_id" required>
                        <option value="">Selecione uma entrada</option>
                        <?php while ($row = $entradas_sem_saida->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                Entrada de Motorista ID: <?php echo $row['motorista_id']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Registrar Saida</button>
            </form>
        </div>

        <div class="visitas-container">
            <h2>Liberar Sala de Visitas</h2>
            <!-- Formulário para liberar salas de reuniões -->
            <form action="../actions/registrar_saida.php" method="post">
                <div class="input-group">
                    <label for="visita_id">Selecione a visita para liberar a sala:</label>
                    <select name="visita_id" required>
                        <option value="">Selecione uma visita</option>
                        <?php while ($row = $visitas_ocupadas->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                Sala: <?php echo $row['sala_nome']; ?> (Visita ID: <?php echo $row['id']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Liberar Sala</button>
            </form>
        </div>
    </main>
</div>

    <?php include('../includes/footer.php'); ?>

</body>
</html>
