<?php
session_start();

require_once('../includes/db.php');

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($conn)) {
    die("Erro: Conexão com o banco de dados não estabelecida.");
}

$user_id = $_SESSION['user_id'];
$query = "SELECT nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$nome_usuario = $user ? $user['nome'] : 'Usuário';

function obterEntradasRealizadas($conn) {
    $query = "SELECT COUNT(*) AS total FROM entradas_saidas WHERE data_saida IS NOT NULL";
    $result = $conn->query($query);
    if (!$result) {
        die("Erro ao buscar entradas realizadas: " . $conn->error);
    }
    $row = $result->fetch_assoc();
    return $row['total'];
}

function obterReunioesHoje($conn) {
    $hoje = date('Y-m-d');
    $query = "SELECT COUNT(*) AS total FROM reunioes WHERE data_visita = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $hoje);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

function obterReunioesSemana($conn) {
    $inicio_semana = date('Y-m-d', strtotime('monday this week'));
    $fim_semana = date('Y-m-d', strtotime('sunday this week'));
    $query = "SELECT COUNT(*) AS total FROM reunioes WHERE data_visita BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }
    $stmt->bind_param("ss", $inicio_semana, $fim_semana);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Portaria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <style>
        /* Reset básico */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }



        /* O conteúdo principal cresce e empurra o footer para baixo */
        .main-content {
            display: flex;
            padding: 20px;
            gap: 20px;
            margin-left: 100px; 
            margin-top: 0;
        }


        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }


        .column {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            gap: 50px;
        }

        .column h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .status-sala {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .status-sala .indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .status-sala .indicator.ocupada {
            background-color: #ff4d4d; 
        }

        .status-sala .indicator.disponivel {
            background-color: #4caf50; 
        }

        .indicadores {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .indicador {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .indicador h4 {
            margin-bottom: 10px;
            color: #555;
        }

        .indicador p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .calendar {
            height: 400px;
        }

        .bottom-section {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            margin-left: 270px; 
            gap: 50px;
            margin-top: 20px;
        }

        .botao-relatorio {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #4caf50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .botao-relatorio:hover {
            background-color: #45a049;
        }

        .botao-relatorio.interrogacao {
            background-color: #ff9800;
        }

        .botao-relatorio.interrogacao:hover {
            background-color: #e68900;
        }

        .footer {
            width: 100%;
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        /* Ajustes para telas menores */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .bottom-section {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                margin-left: 0;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <?php include('../includes/sidebar.php'); ?>

        <main class="main-content">
            <!-- Coluna da Esquerda: Status das Salas -->
            <div class="column">
                <h3>Status das Salas</h3>
                <?php
                // Buscar status das salas
                $query_salas = "SELECT id, nome, ocupada FROM salas";
                $result_salas = $conn->query($query_salas);
                while ($sala = $result_salas->fetch_assoc()):
                ?>
                    <div class="status-sala">
                        <div class="indicator <?= $sala['ocupada'] ? 'ocupada' : 'disponivel'; ?>"></div>
                        <span><?= htmlspecialchars($sala['nome']); ?></span>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Coluna do Meio: Indicadores -->
            <div class="column">
                <h3>Indicadores</h3>
                <div class="indicadores">
                    <div class="indicador">
                        <h4>Entradas Realizadas</h4>
                        <p><?= obterEntradasRealizadas($conn); ?></p>
                    </div>
                    <div class="indicador">
                        <h4>Reuniões Agendadas para Hoje</h4>
                        <p><?= obterReunioesHoje($conn); ?></p>
                    </div>
                    <div class="indicador">
                        <h4>Reuniões Agendadas para a Semana</h4>
                        <p><?= obterReunioesSemana($conn); ?></p>
                    </div>
                </div>
            </div>

            <!-- Coluna da Direita: Calendário -->
            <div class="column">
                <h3>Calendário</h3>
                <div id="calendar" class="calendar"></div>
            </div>
            <div class="button-section">
                <div class="botao-relatorio" onclick="gerarRelatorioSemanal()">Relatório Semanal</div>
                <div class="botao-relatorio" onclick="gerarRelatorioMensal()">Relatório Mensal</div>
                <div class="botao-relatorio interrogacao" onclick="mostrarAjuda()">?</div>
            </div>

        </main>



    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script>
        // Inicializar o calendário
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'https://fullcalendar.io/api/demo-feeds/events.json', // Substitua pela sua API
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            });
            calendar.render();
        });

        function gerarRelatorioSemanal() {
            alert("Relatório Semanal gerado!");
        }

        function gerarRelatorioMensal() {
            alert("Relatório Mensal gerado!");
        }

        function mostrarAjuda() {
            alert("Ajuda: Em breve mais informações aqui!");
        }
    </script>
        <?php include('../includes/footer.php'); ?>
</body>
</html>