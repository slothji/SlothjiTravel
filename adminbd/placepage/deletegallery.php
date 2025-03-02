<?php
include '../db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบคำขอ
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['galleryID'])) {
    $galleryID = $data['galleryID'];

    // ดึงชื่อไฟล์จากฐานข้อมูลเพื่อลบไฟล์
    $sql = "SELECT GalleryImg FROM gallery WHERE GalleryID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $galleryID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $filePath = '../uploads/gallerys/' . $row['GalleryImg'];

        // ลบไฟล์ในเซิร์ฟเวอร์
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                echo "ลบไฟล์ $filePath สำเร็จ";
            } else {
                echo "ไม่สามารถลบไฟล์ $filePath ได้";
            }
        }

        // ลบข้อมูลในฐานข้อมูล
        $deleteSQL = "DELETE FROM gallery WHERE GalleryID = ?";
        $deleteStmt = $conn->prepare($deleteSQL);
        $deleteStmt->bind_param("i", $galleryID);

        if ($deleteStmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ไม่สามารถลบข้อมูลในฐานข้อมูลได้']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ไม่พบรูปภาพ']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ข้อมูล GalleryID ไม่ครบถ้วน']);
}
