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

    $sqlImg = "SELECT PlaceImg FROM places WHERE PlaceID IN ($placeIDs_str)";
    $resultImg = $conn->query($sqlImg);
    while ($row = $resultImg->fetch_assoc()) {
        $imagePath = realpath("./uploads/places/" . $row['PlaceImg']);
        if (!empty($row['PlaceImg']) && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $sqlGallery = "SELECT GalleryImg FROM gallery WHERE PlaceID IN ($placeIDs_str)";
    $resultGallery = $conn->query($sqlGallery);
    while ($row = $resultGallery->fetch_assoc()) {
        $galleryImagePath = realpath("./uploads/gallerys/" . $row['GalleryImg']);
        if (!empty($row['GalleryImg']) && file_exists($galleryImagePath)) {
            unlink($galleryImagePath);
        }
    }

    $conn->query("DELETE FROM gallery WHERE PlaceID IN ($placeIDs_str)");

    $deleteSuccess = $conn->query("DELETE FROM places WHERE PlaceID IN ($placeIDs_str)");

    ob_clean();
    header('Content-Type: application/json');

    if ($deleteSuccess) {
        echo json_encode(['success' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }
    exit();
}
