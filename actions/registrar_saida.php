<?php
// registrar_saida.php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = trim($_POST['data']);
    $hora = trim($_POST['hora']);
    $assunto = trim($_POST['assunto']);

    if (empty($data) || empty($hora) || empty($assunto)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios.";
        header('Location: ../pages/saida.php');
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO reunioes (data, hora, assunto) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data, $hora, $assunto);
        $stmt->execute();
        $_SESSION['success'] = "Reunião agendada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro ao agendar reunião: " . $e->getMessage();
    } finally {
        header('Location: ../pages/saida.php');
        exit();
    }
}
?>
