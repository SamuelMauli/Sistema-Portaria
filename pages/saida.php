<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/sidebar.php');
require_once('../includes/db.php');

$conn = getConnection();

$entradas_sem_saida = $conn->query("SELECT id, motorista_id FROM entradas_saidas WHERE data_saida IS NULL");

$visitas_ocupadas = $conn->query("SELECT av.id, av.sala_aeb_id, s.nome AS sala_nome 
                                  FROM agenda_visitas av 
                                  JOIN sala_aeb s ON av.id = s.id 
                                  WHERE av.hora_visita_saida IS NULL");

?>

<div class="content">
    <h2>Registrar Saída</h2>

    <!-- Formulário para registrar saída de entrada -->
    <form action="../actions/registrar_saida.php" method="post">
        <div class="input-group">
            <label for="entrada_id">Selecione a entrada sem saída registrada:</label>
            <select name="entrada_id" required>
                <option value="">Selecione uma entrada</option>
                <?php while ($row = $entradas_sem_saida->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>">
                        Entrada de Motorista ID: <?php echo $row['motorista_id']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit">Registrar Saída</button>
    </form>

    <br>

    <!-- Formulário para liberar salas de reuniões -->
    <h2>Liberar Sala de Visitas</h2>
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

<?php include('../includes/footer.php'); ?>
