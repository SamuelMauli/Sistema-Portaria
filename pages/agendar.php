<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('../includes/sidebar.php');
?>

<h2>Agendar Reunião</h2>
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
