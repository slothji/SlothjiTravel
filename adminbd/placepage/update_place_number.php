<?php
require_once '../db.php'; // สำหรับเชื่อมต่อฐานข้อมูล

if (isset($_POST['sortData'])) {
    $sortData = json_decode($_POST['sortData'], true);
    foreach ($sortData as $item) {
        $stmt = $conn->prepare("UPDATE places SET PlaceNumbers = ? WHERE PlaceID = ?");
        $stmt->bind_param("ii", $item['PlaceNumbers'], $item['PlaceID']);
        $stmt->execute();
    }
    echo "success";
}
