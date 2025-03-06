<?php
include '../db.php';

header('Content-Type: application/json');

if (isset($_POST['AdminID'])) {
    $adminID = $_POST['AdminID'];

    $stmt = $conn->prepare("DELETE FROM admins WHERE AdminID = ?");
    $stmt->bind_param("i", $adminID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "ลบบัญชีผู้ใช้เรียบร้อย"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่สามารถลบข้อมูลได้"]);
    }

    $stmt->close();
}
