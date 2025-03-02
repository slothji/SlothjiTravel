<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['TypeID'] ?? null; // ตรวจสอบคีย์ 'TypeID'

    if (empty($id)) {
        echo "error: Missing TypeID";
        exit;
    }

    // ลบข้อมูลจากตาราง visitcount ที่มี TypeID ตรงกัน
    $stmt = $conn->prepare("DELETE FROM visitcount WHERE TypeID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();

        // ลบข้อมูลจากตาราง typeplace หลังจากลบข้อมูลใน visitcount
        $stmt = $conn->prepare("DELETE FROM typeplace WHERE TypeID = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error deleting from typeplace: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "error deleting from visitcount: " . $conn->error;
    }

    $conn->close();
} else {
    echo "error: Invalid request method";
}
