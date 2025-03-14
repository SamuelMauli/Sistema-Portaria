<?php
require_once('../includes/db.php');

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $conn = conectarBancoDeDados();

    $sql = "SELECT motorista, transportadora, finalidade, responsavel, aeb FROM entradas 
            WHERE motorista LIKE ? 
            OR transportadora LIKE ? 
            OR finalidade LIKE ? 
            OR responsavel LIKE ? 
            OR aeb LIKE ? 
            LIMIT 10";

    $stmt = $conn->prepare($sql);
    $likeQuery = "%$query%";
    $stmt->bind_param("sssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $entradas = [];
    while ($row = $result->fetch_assoc()) {
        $entradas[] = $row;
    }

    echo json_encode($entradas);
    exit();
}
?>
