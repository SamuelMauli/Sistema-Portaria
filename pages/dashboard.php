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

    <!-- Conectando o CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
    <!-- Incluir a sidebar -->
    <?php include('../includes/sidebar.php'); ?>

    <!-- Conte?do principal -->
    <main class="main-content">
        <h2>Bem-vindo, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Escolha uma das op??es no menu lateral.</p>
    </main>
</div>

<!-- Incluir o rodap? -->
<?php include('../includes/footer.php'); ?>

</body>
</html>
