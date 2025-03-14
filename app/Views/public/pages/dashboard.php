<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../includes/db.php');
$conn = conectarBancoDeDados();

// Buscar salas cadastradas
$query_salas = "SELECT s.id, s.nome, s.ocupada, r.nome AS responsavel, av.data_visita, av.hora_visita 
                FROM salas s
                LEFT JOIN agenda_visitas av ON s.id = av.sala_aeb_id
                LEFT JOIN responsaveis r ON av.responsavel_aeb_id = r.id";
$result_salas = $conn->query($query_salas);

// Buscar eventos do calendário
$query_eventos = "SELECT id, titulo, descricao, data_inicio, data_fim FROM eventos_calendario";
$result_eventos = $conn->query($query_eventos);
$eventos = [];
while ($row = $result_eventos->fetch_assoc()) {
    $eventos[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Portaria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css">
    <style>
        .dashboard-container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }
        .salas-list {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .sala-item {
            width: 48%;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }
        .sala-status {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: green;
        }
        .sala-status.ocupada {
            background: red;
        }
        .calendar {
            flex: 2;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php include 'partials/sidebar.php'; ?>
        <main class="dashboard-container">
            <div class="salas-list">
                <h2>Salas Cadastradas</h2>
                <?php while ($sala = $result_salas->fetch_assoc()): ?>
                    <div class="sala-item">
                        <div>
                            <strong><?= htmlspecialchars($sala['nome']); ?></strong><br>
                            <small>Responsável: <?= htmlspecialchars($sala['responsavel'] ?? 'N/A'); ?></small><br>
                            <small>Horário: <?= htmlspecialchars($sala['hora_visita'] ?? 'N/A'); ?></small>
                        </div>
                        <div class="sala-status <?= $sala['ocupada'] ? 'ocupada' : ''; ?>"></div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="calendar">
                <h2>Calendário</h2>
                <div id="calendar"></div>
            </div>
        </main>
    </div>
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Adicionar Evento</h2>
            <input type="text" id="eventTitle" placeholder="Título do Evento">
            <textarea id="eventDescription" placeholder="Descrição"></textarea>
            <input type="datetime-local" id="eventStart">
            <input type="datetime-local" id="eventEnd">
            <button id="saveEvent">Salvar</button>
            <button id="deleteEvent" style="display: none;">Excluir</button>
            <button id="closeModal">Fechar</button>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?= json_encode($eventos); ?>,
                eventClick: function(info) {
                    document.getElementById('modalTitle').textContent = 'Editar Evento';
                    document.getElementById('eventTitle').value = info.event.title;
                    document.getElementById('eventDescription').value = info.event.extendedProps.description;
                    document.getElementById('eventStart').value = info.event.start.toISOString().slice(0, 16);
                    document.getElementById('eventEnd').value = info.event.end ? info.event.end.toISOString().slice(0, 16) : '';
                    document.getElementById('eventModal').style.display = 'flex';
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>
