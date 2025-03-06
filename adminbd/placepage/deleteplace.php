<?php
session_start();
include '../db.php';

if (isset($_GET['PlaceID'])) {
    $placeID = $_GET['PlaceID'];
    $response = ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];

    $sql = "SELECT PlaceImg FROM places WHERE PlaceID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $placeID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $place = $result->fetch_assoc();
        $placeImg = $place['PlaceImg'];

        $imagePath = './uploads/places/' . $placeImg;
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $response['message'] = "ลบไฟล์รูปภาพ $imagePath สำเร็จ";
            } else {
                $response['message'] = "ไม่สามารถลบไฟล์รูปภาพ $imagePath ได้";
                echo json_encode($response);
                exit();
            }
        }
    }

    $sqlGallery = "SELECT GalleryImg FROM gallery WHERE PlaceID = ?";

    $stmtGallery = $conn->prepare($sqlGallery);
    $stmtGallery->bind_param("i", $placeID);
    $stmtGallery->execute();
    $resultGallery = $stmtGallery->get_result();

    if ($resultGallery->num_rows > 0) {
        while ($gallery = $resultGallery->fetch_assoc()) {
            $galleryImg = $gallery['GalleryImg'];
            $galleryImagePath = './uploads/gallerys/' . $galleryImg;
            if (file_exists($galleryImagePath)) {
                if (unlink($galleryImagePath)) {
                    $response['message'] .= " ลบไฟล์ใน Gallery $galleryImagePath สำเร็จ";
                } else {
                    $response['message'] .= " ไม่สามารถลบไฟล์ใน Gallery $galleryImagePath ได้";
                    echo json_encode($response);
                    exit();
                }
            }
        }
    }

    $sqlDeleteGallery = "DELETE FROM gallery WHERE PlaceID = ?";
    $stmtDeleteGallery = $conn->prepare($sqlDeleteGallery);
    $stmtDeleteGallery->bind_param("i", $placeID);
    $stmtDeleteGallery->execute();

    $sqlDeletePlace = "DELETE FROM places WHERE PlaceID = ?";
    $stmtDeletePlace = $conn->prepare($sqlDeletePlace);
    $stmtDeletePlace->bind_param("i", $placeID);

    if ($stmtDeletePlace->execute()) {
        $response = ['success' => true, 'message' => 'ลบข้อมูลสำเร็จ'];
    } else {
        $response['message'] = 'ไม่สามารถลบข้อมูลสถานที่ได้';
    }

    echo json_encode($response);
    exit();
} else {
    $response = ['success' => false, 'message' => 'ไม่พบ PlaceID'];
    echo json_encode($response);
    exit();
}
