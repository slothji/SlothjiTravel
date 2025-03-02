<?php
include '../db.php';

header('Content-Type: application/json'); // กำหนดให้ส่ง JSON กลับ

// ตรวจสอบค่าที่ส่งมาผ่าน AJAX
if (isset($_POST['AdminID'])) {
    $adminID = $_POST['AdminID'];

    // คำสั่ง SQL เพื่อลบแอดมิน
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
