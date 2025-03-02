<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placeIDs'])) {
    $placeIDs = $_POST['placeIDs'];

    if (empty($placeIDs)) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลที่ต้องการลบ']);
        exit();
    }

    $placeIDs_str = implode(',', array_map('intval', $placeIDs));

    // ✅ 1. ลบไฟล์จาก places
    $sqlImg = "SELECT PlaceImg FROM places WHERE PlaceID IN ($placeIDs_str)";
    $resultImg = $conn->query($sqlImg);
    while ($row = $resultImg->fetch_assoc()) {
        $imagePath = realpath("./uploads/places/" . $row['PlaceImg']);
        if (!empty($row['PlaceImg']) && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // ✅ 2. ลบไฟล์จาก gallery
    $sqlGallery = "SELECT GalleryImg FROM gallery WHERE PlaceID IN ($placeIDs_str)";
    $resultGallery = $conn->query($sqlGallery);
    while ($row = $resultGallery->fetch_assoc()) {
        $galleryImagePath = realpath("./uploads/gallerys/" . $row['GalleryImg']);
        if (!empty($row['GalleryImg']) && file_exists($galleryImagePath)) {
            unlink($galleryImagePath);
        }
    }

    // ✅ 3. ลบข้อมูลจากตาราง gallery
    $conn->query("DELETE FROM gallery WHERE PlaceID IN ($placeIDs_str)");

    // ✅ 4. ลบข้อมูลจากตาราง places
    $deleteSuccess = $conn->query("DELETE FROM places WHERE PlaceID IN ($placeIDs_str)");

    // 🛑 **ล้าง Output ก่อนส่ง JSON**
    ob_clean();
    header('Content-Type: application/json');

    if ($deleteSuccess) {
        echo json_encode(['success' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }
    exit();
}
