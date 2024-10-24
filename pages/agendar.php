<<<<<<< HEAD
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

include('../includes/sidebar.php');
include('../includes/db.php');

try {
    // Buscar salas disponíveis
    $query_salas = "SELECT id, nome FROM sala_aeb";
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
</head>
<body>
    <div class="container">
        <h2>Agendar Reunião</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="../actions/agendar_reuniao_action.php" method="post">
            <label for="visitante_id">ID do Visitante:</label>
            <input type="number" name="visitante_id" required><br>

            <label for="data">Data:</label>
            <input type="date" name="data" required><br>

            <label for="hora_inicio">Hora de Início:</label>
            <input type="time" name="hora_inicio" required><br>

            <label for="hora_fim">Hora de Fim:</label>
            <input type="time" name="hora_fim" required><br>

            <label for="finalidade_id">Finalidade:</label>
            <select name="finalidade_id" required>
                <option value="">Selecione a finalidade</option>
                <?php while ($finalidade = $result_finalidades->fetch_assoc()): ?>
                    <option value="<?= $finalidade['id']; ?>"><?= $finalidade['descricao']; ?></option>
                <?php endwhile; ?>
            </select><br>

            <label for="responsavel_aeb_id">ID do Responsável (AEB):</label>
            <input type="number" name="responsavel_aeb_id" required><br>

            <label for="observacoes">Observações:</label>
            <textarea name="observacoes" rows="3"></textarea><br>

            <label for="sala_id">Sala:</label>
            <select name="sala_id" required>
                <option value="">Selecione a sala</option>
                <?php while ($sala = $result_salas->fetch_assoc()): ?>
                    <option value="<?= $sala['id']; ?>"><?= $sala['nome']; ?></option>
                <?php endwhile; ?>
            </select><br>

            <label for="quantidade_pessoas">Quantidade de Pessoas:</label>
            <input type="number" name="quantidade_pessoas" required><br>

            <label for="bebidas">Necessário Bebidas?</label>
            <input type="checkbox" name="bebidas" value="1"><br>

            <button type="submit">Agendar Reunião</button>
        </form>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
=======
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/sidebar.php');
?>

<h2>Agendar Reuni�o</h2>
<form action="../actions/agendar_reuniao_action.php" method="post">
    <label for="data">Data:</label>
    <input type="date" name="data" required><br>
    <label for="hora">Hora:</label>
    <input type="time" name="hora" required><br>
    <label for="assunto">Assunto:</label>
    <input type="text" name="assunto" required><br>
    <button type="submit">Agendar</button>
</form>

<?php include('../includes/footer.php'); ?>
>>>>>>> ccc14c2 (Initial commit)
