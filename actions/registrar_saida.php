<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visita_id = intval($_POST['visita_id']);

    try {
        // Marca a sala como livre
        $conn = conectarBancoDeDados();
        $stmt = $conn->prepare("UPDATE salas SET ocupada = 0 WHERE id = (SELECT sala_aeb_id FROM agenda_visitas WHERE id = ?)");
        $stmt->bind_param("i", $visita_id);
        $stmt->execute();

        // Marca a visita como finalizada
        $stmt = $conn->prepare("UPDATE agenda_visitas SET hora_visita_saida = NOW() WHERE id = ?");
        $stmt->bind_param("i", $visita_id);
        $stmt->execute();

        $_SESSION['success'] = "Sala liberada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['erro_visita'] = "Erro ao liberar sala: " . $e->getMessage();
    } finally {
        header('Location: ../pages/saida.php');
        exit();
    }
}
?>