<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typeID = $_POST['TypeID'];
    $typeTitle = $_POST['TypeTitle'];
    $typeDetail = $_POST['TypeDetail'];

    // ค้นหาไฟล์ภาพเก่าในฐานข้อมูล
    $oldImgQuery = $conn->prepare("SELECT TypeImg FROM typeplace WHERE TypeID = ?");
    $oldImgQuery->bind_param("i", $typeID);
    $oldImgQuery->execute();
    $result = $oldImgQuery->get_result();
    $oldImg = $result->fetch_assoc()['TypeImg'];
    $oldImgQuery->close();

    $fileName = null; // ค่าเริ่มต้น

    // ตรวจสอบและจัดการอัปโหลดไฟล์ภาพใหม่
    if (isset($_FILES['TypeImg']) && $_FILES['TypeImg']['error'] == 0) {
        $uploadDir = 'uploadType/';
        $fileName = basename($_FILES['TypeImg']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['TypeImg']['tmp_name'], $targetFilePath)) {
            // ลบไฟล์ภาพเก่า
            if ($oldImg && file_exists($uploadDir . $oldImg)) {
                unlink($uploadDir . $oldImg);
            }
        } else {
            echo 'Failed to upload image.';
            exit;
        }
    } else {
        $fileName = $oldImg; // ใช้ชื่อไฟล์ภาพเก่า
    }

    // อัปเดตฐานข้อมูล
    $stmt = $conn->prepare("UPDATE typeplace SET TypeTitle = ?, TypeImg = ?, TypeDetail = ? WHERE TypeID = ?");
    $stmt->bind_param("sssi", $typeTitle, $fileName, $typeDetail, $typeID);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
}
