<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typeID = $_POST['TypeID'];
    $typeTitle = $_POST['TypeTitle'];
    $typeDetail = $_POST['TypeDetail'];

    $oldImgQuery = $conn->prepare("SELECT TypeImg FROM typeplace WHERE TypeID = ?");
    $oldImgQuery->bind_param("i", $typeID);
    $oldImgQuery->execute();
    $result = $oldImgQuery->get_result();
    $oldImg = $result->fetch_assoc()['TypeImg'];
    $oldImgQuery->close();

    $fileName = null;

    if (isset($_FILES['TypeImg']) && $_FILES['TypeImg']['error'] == 0) {
        $uploadDir = 'uploadType/';
        $fileName = basename($_FILES['TypeImg']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['TypeImg']['tmp_name'], $targetFilePath)) {
            if ($oldImg && file_exists($uploadDir . $oldImg)) {
                unlink($uploadDir . $oldImg);
            }
        } else {
            echo 'Failed to upload image.';
            exit;
        }
    } else {
        $fileName = $oldImg;
    }

    $stmt = $conn->prepare("UPDATE typeplace SET TypeTitle = ?, TypeImg = ?, TypeDetail = ? WHERE TypeID = ?");
    $stmt->bind_param("sssi", $typeTitle, $fileName, $typeDetail, $typeID);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
}
