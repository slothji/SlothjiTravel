<?php
include '../db.php'; // เชื่อมต่อฐานข้อมูล

$data = json_decode(file_get_contents("php://input"), true); // รับข้อมูลจาก JSON
$placeID = $data['PlaceID'];
$isVisible = $data['isVisible'];

// เตรียมคำสั่ง SQL เพื่ออัปเดตสถานะ
$stmt = $conn->prepare("UPDATE places SET isVisible = ? WHERE PlaceID = ?");
$stmt->bind_param("ii", $isVisible, $placeID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating visibility"]);
}
