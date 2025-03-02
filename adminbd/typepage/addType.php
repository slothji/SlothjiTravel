<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['TypeImg']) && $_FILES['TypeImg']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploadType/';
        $fileName = basename($_FILES['TypeImg']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['TypeImg']['tmp_name'], $targetFilePath)) {
            $typeTitle = $_POST['TypeTitle'];
            $typeDetail = $_POST['TypeDetail'];

            $stmt = $conn->prepare("INSERT INTO typeplace (TypeTitle, TypeImg, TypeDetail) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $typeTitle, $fileName, $typeDetail);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            echo 'Failed to move uploaded file.';
        }
    } else {
        $error = $_FILES['TypeImg']['error'] ?? 'No file uploaded';
        echo "File upload error: $error";
    }
}
