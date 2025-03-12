<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Portaria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <aside class="sidebar collapsed">
        <div class="logo">
            <img src="../assets/img/aeb-logo-white.png" alt="Logo AEB">
        </div>
        <button class="toggle-button" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <nav class="nav-menu">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span class="menu-text"> Dashboard</span></a></li>
                <li><a href="entrada.php"><i class="fas fa-sign-in-alt"></i><span class="menu-text"> Entrada</span></a></li>
                <li><a href="saida.php"><i class="fas fa-sign-out-alt"></i><span class="menu-text"> Saida</span></a></li>
                <li><a href="agendar.php"><i class="fas fa-calendar-check"></i><span class="menu-text"> Agenda</span></a></li>
                <li><a href="cadastro.php"><i class="fas fa-address-book"></i><span class="menu-text"> Cadastrar</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i><span class="menu-text"> Sair</span></a></li>
            </ul>
        </nav>
    </aside>

</div>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('collapsed');
        sidebar.classList.toggle('expanded');
    }
</script>

</body>
</html>
