<?php
function uploadImage($file, $uploadDir)
{
    $allowedTypes = ['image/jpeg', 'image/png'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (is_array($file['name'])) {
        $fileCount = count($file['name']);
        $results = [];

        for ($i = 0; $i < $fileCount; $i++) {
            $singleFile = [
                'name' => $file['name'][$i],
                'type' => $file['type'][$i],
                'tmp_name' => $file['tmp_name'][$i],
                'error' => $file['error'][$i],
                'size' => $file['size'][$i]
            ];

            $uploadResult = uploadSingleImage($singleFile, $uploadDir);
            $results[] = $uploadResult;
        }

        return $results;
    } else {
        return uploadSingleImage($file, $uploadDir);
    }
}

function uploadSingleImage($file, $uploadDir)
{
    $allowedTypes = ['image/jpeg', 'image/png'];
    $maxSize = 5 * 1024 * 1024;

    // ตรวจสอบการอัปโหลดไฟล์
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error: ' . $file['error']];
    }

    // ตรวจสอบประเภทไฟล์
    $fileType = $file['type'];
    $fileSize = $file['size'];
    $tmpName = $file['tmp_name'];
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type.'];
    }

    // สร้างชื่อไฟล์ใหม่
    $date = date('YmdHis');
    $randomStr = substr(md5(uniqid()), 0, 4);
    $randomNum = rand(111, 999);
    $newFileName = "PL{$date}_{$randomStr}{$randomNum}.{$extension}";

    // สร้างโฟลเดอร์หากยังไม่มี
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . '/' . $newFileName;

    // หากขนาดไฟล์เกิน 5MB ให้ทำการปรับขนาด
    if ($fileSize > $maxSize) {
        // คำนวณขนาดใหม่ที่จะทำการ resize
        $newWidth = 1520; // ความกว้างใหม่
        $newHeight = 725; // ความสูงจะคำนวณอัตโนมัติ

        // เรียกใช้ฟังก์ชัน resizeImage
        $resizeResult = resizeImage($tmpName, $filePath, $newWidth, $newHeight);

        if ($resizeResult['success']) {
            return ['success' => true, 'fileName' => $newFileName];
        } else {
            return ['success' => false, 'error' => $resizeResult['error']];
        }
    } else {
        // ถ้าขนาดไฟล์ไม่เกิน 5MB ก็อัพโหลดไฟล์ปกติ
        move_uploaded_file($tmpName, $filePath);
        return ['success' => true, 'fileName' => $newFileName];
    }
}

function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight)
{
    list($width, $height, $imageType) = getimagesize($sourcePath);

    // ตรวจสอบประเภทไฟล์และสร้างรูปภาพต้นฉบับ
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return ['success' => false, 'error' => 'Unsupported image type'];
    }

    // คำนวณขนาดใหม่ตามอัตราส่วน
    $aspectRatio = $width / $height;

    if ($newWidth == 0 && $newHeight != 0) {
        $newWidth = $newHeight * $aspectRatio;
    } elseif ($newHeight == 0 && $newWidth != 0) {
        $newHeight = $newWidth / $aspectRatio;
    } elseif ($newWidth == 0 && $newHeight == 0) {
        return ['success' => false, 'error' => 'Invalid dimensions'];
    }

    $newWidth = intval($newWidth); // แปลงเป็นจำนวนเต็ม
    $newHeight = intval($newHeight); // แปลงเป็นจำนวนเต็ม

    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // คงค่า transparency สำหรับ PNG และ GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
    }

    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // บันทึกรูปภาพที่ปรับขนาดแล้ว
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $destinationPath, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $destinationPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($resizedImage, $destinationPath);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($resizedImage);

    return ['success' => true];
}
