<?php
session_start();
include '../db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่า PlaceID มาหรือไม่
if (isset($_GET['PlaceID'])) {
    $placeID = $_GET['PlaceID'];
    $response = ['success' => false, 'message' => 'เกิดข้อผิดพลาด'];

    // คำสั่ง SQL เพื่อลบข้อมูลสถานที่และดึงชื่อไฟล์ภาพที่เกี่ยวข้อง
    $sql = "SELECT PlaceImg FROM places WHERE PlaceID = ?";

    // ใช้ Prepared Statement เพื่อความปลอดภัย
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $placeID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $place = $result->fetch_assoc();
        $placeImg = $place['PlaceImg']; // ชื่อไฟล์ของรูปภาพหลักที่จะลบ

        // ตรวจสอบว่าไฟล์ภาพหลักมีอยู่ในโฟลเดอร์และลบ
        $imagePath = './uploads/places/' . $placeImg;
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $response['message'] = "ลบไฟล์รูปภาพ $imagePath สำเร็จ";
            } else {
                $response['message'] = "ไม่สามารถลบไฟล์รูปภาพ $imagePath ได้";
                echo json_encode($response); // ส่งค่าผลลัพธ์กลับหากลบไฟล์ไม่ได้
                exit(); // หยุดการทำงานหากไม่สามารถลบไฟล์ได้
            }
        }
    }

    // คำสั่ง SQL เพื่อลบข้อมูลจากตาราง gallery ที่เกี่ยวข้องกับ PlaceID นี้
    $sqlGallery = "SELECT GalleryImg FROM gallery WHERE PlaceID = ?";

    // ใช้ Prepared Statement เพื่อความปลอดภัย
    $stmtGallery = $conn->prepare($sqlGallery);
    $stmtGallery->bind_param("i", $placeID);
    $stmtGallery->execute();
    $resultGallery = $stmtGallery->get_result();

    if ($resultGallery->num_rows > 0) {
        while ($gallery = $resultGallery->fetch_assoc()) {
            $galleryImg = $gallery['GalleryImg']; // ชื่อไฟล์รูปภาพใน gallery

            // ตรวจสอบว่าไฟล์ภาพใน gallery มีอยู่ในโฟลเดอร์และลบ
            $galleryImagePath = './uploads/gallerys/' . $galleryImg;
            if (file_exists($galleryImagePath)) {
                if (unlink($galleryImagePath)) {
                    $response['message'] .= " ลบไฟล์ใน Gallery $galleryImagePath สำเร็จ";
                } else {
                    $response['message'] .= " ไม่สามารถลบไฟล์ใน Gallery $galleryImagePath ได้";
                    echo json_encode($response); // ส่งค่าผลลัพธ์กลับหากลบไฟล์ไม่ได้
                    exit(); // หยุดการทำงานหากไม่สามารถลบไฟล์ได้
                }
            }
        }
    }

    // ลบข้อมูลในตาราง gallery
    $sqlDeleteGallery = "DELETE FROM gallery WHERE PlaceID = ?";
    $stmtDeleteGallery = $conn->prepare($sqlDeleteGallery);
    $stmtDeleteGallery->bind_param("i", $placeID);
    $stmtDeleteGallery->execute();

    // คำสั่ง SQL เพื่อลบข้อมูลจากตาราง places
    $sqlDeletePlace = "DELETE FROM places WHERE PlaceID = ?";
    $stmtDeletePlace = $conn->prepare($sqlDeletePlace);
    $stmtDeletePlace->bind_param("i", $placeID);

    if ($stmtDeletePlace->execute()) {
        $response = ['success' => true, 'message' => 'ลบข้อมูลสำเร็จ'];
    } else {
        $response['message'] = 'ไม่สามารถลบข้อมูลสถานที่ได้';
    }

    // ส่งข้อมูล JSON กลับไป
    echo json_encode($response);
    exit(); // หยุดการทำงานหลังจากส่งข้อมูล JSON
} else {
    // หากไม่มีการส่ง PlaceID มาจะส่งค่าผลลัพธ์เป็น error
    $response = ['success' => false, 'message' => 'ไม่พบ PlaceID'];
    echo json_encode($response);
    exit(); // หยุดการทำงานหลังจากส่งข้อมูล JSON
}
