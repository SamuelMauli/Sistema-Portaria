<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/Login-style.css">
</head>
<body>

<!-- Formulário de Login -->
<form action="../actions/login_action.php" method="post">
    <h2>Login</h2>
    <label for="username">Usuario:</label>
    <input type="text" name="username" required><br>

    <label for="password">Senha:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Entrar</button>
</form>

<?php include('../includes/footer.php'); ?>

<!-- Exibe a mensagem de erro se existir -->
<?php if (isset($_SESSION['login_error'])): ?>
    <div class="error-message">
        <p><?php echo $_SESSION['login_error']; ?></p>
    </div>
    <?php unset($_SESSION['login_error']); ?>
<?php endif; ?>

</body>
</html>
