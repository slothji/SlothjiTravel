<?php
include '../db.php';
if (isset($_POST['HomeID'])) {
    $id = $_POST['HomeID'];
    $stmt = $conn->prepare("SELECT * FROM homeslide WHERE HomeID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode($result);
}
