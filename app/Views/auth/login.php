<?php
session_start();

// Redireciona caso o usuário já esteja logado
if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard');
    exit();
}

// Verificação e manipulação de erros de login
if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
} else {
    $login_error = null;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   <!-- <link rel="stylesheet" href="..public/assets/css/Login-style.css">  -->  
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        form {
            background-color: #fff;
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
            display: flex;
        }
        input {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        input:focus {
            border-color: #3498db;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Formulário de Login -->
<form action="/login" method="post"> <!-- Corrected path -->
    <h2>Portaria AEB</h2>

    <!-- Input de usuário -->
    <label for="username">Usuário:</label>
    <input type="text" name="username" required>

    <!-- Input de senha -->
    <label for="password">Senha:</label>
    <input type="password" name="password" required>

    <!-- Botão de Login -->
    <button type="submit">Entrar</button>
</form>

<!-- Exibe a mensagem de erro de login -->
<?php if ($login_error): ?>
    <div class="error-message">
        <p><?php echo $login_error; ?></p>
    </div>
<?php endif; ?>

</body>
</html>