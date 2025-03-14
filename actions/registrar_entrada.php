<?php
// registrar_entrada.php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $documento = trim($_POST['documento']);

    if (empty($nome) || empty($documento)) {
        $_SESSION['error'] = "Nome e documento são obrigatórios.";
        header('Location: ../pages/entrada.php');
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO entradas (nome, documento, data_hora) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $nome, $documento);
        $stmt->execute();
        $_SESSION['success'] = "Entrada registrada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro ao registrar entrada: " . $e->getMessage();
    } finally {
        header('Location: ../pages/entrada.php');
        exit();
    }
}
?>
