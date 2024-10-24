<<<<<<< HEAD
<?php
// registrar_saida.php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = trim($_POST['data']);
    $hora = trim($_POST['hora']);
    $assunto = trim($_POST['assunto']);

    if (empty($data) || empty($hora) || empty($assunto)) {
        $_SESSION['error'] = "Todos os campos sÃ£o obrigatÃ³rios.";
        header('Location: ../pages/saida.php');
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO reunioes (data, hora, assunto) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data, $hora, $assunto);
        $stmt->execute();
        $_SESSION['success'] = "ReuniÃ£o agendada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro ao agendar reuniÃ£o: " . $e->getMessage();
    } finally {
        header('Location: ../pages/saida.php');
        exit();
    }
}
?>
=======
<?php
session_start();
require_once('../includes/db.php');

$data = $_POST['data'];
$hora = $_POST['hora'];
$assunto = $_POST['assunto'];

// Insere a reunião no banco de dados
$stmt = $conn->prepare("INSERT INTO reunioes (data, hora, assunto) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $data, $hora, $assunto);

if ($stmt->execute()) {
    echo "Reunião agendada com sucesso!";
} else {
    echo "Erro ao agendar reunião.";
}
?>
>>>>>>> ccc14c2 (Initial commit)
