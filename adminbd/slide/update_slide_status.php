<?php
include '../db.php'; // เชื่อมต่อฐานข้อมูล

$data = json_decode(file_get_contents("php://input"), true); // รับข้อมูลจาก JSON
$HomeID = $data['HomeID'];
$HomeStatus = $data['HomeStatus'];

// เตรียมคำสั่ง SQL เพื่ออัปเดตสถานะ
$stmt = $conn->prepare("UPDATE homeslide SET HomeStatus = ? WHERE HomeID = ?");
$stmt->bind_param("ii", $HomeStatus, $HomeID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating visibility"]);
}
