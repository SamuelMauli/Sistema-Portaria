<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Portaria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php include('../includes/sidebar.php'); ?>

        <main class="main-content">
            <h2>Bem-vindo, <?= $_SESSION['username']; ?>!</h2>
            <p>Escolha uma das opções no menu lateral.</p>
        </main>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>