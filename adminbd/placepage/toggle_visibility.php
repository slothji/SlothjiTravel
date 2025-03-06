<?php
include '../db.php';

$data = json_decode(file_get_contents("php://input"), true);
$placeID = $data['PlaceID'];
$isVisible = $data['isVisible'];

$stmt = $conn->prepare("UPDATE places SET isVisible = ? WHERE PlaceID = ?");
$stmt->bind_param("ii", $isVisible, $placeID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating visibility"]);
}
