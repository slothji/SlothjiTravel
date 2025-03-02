<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['HomeID'];
    $imageName = '';

    if (!empty($_FILES['HomeImg']['name'])) {
        $targetDir = "uploadslide/";
        $imageName = time() . "_" . basename($_FILES['HomeImg']['name']);
        $targetFilePath = $targetDir . $imageName;
        move_uploaded_file($_FILES['HomeImg']['tmp_name'], $targetFilePath);
    }

    if ($imageName) {
        $stmt = $conn->prepare("UPDATE homeslide SET HomeImg = ? WHERE HomeID = ?");
        $stmt->bind_param("si", $imageName, $id);
    } else {
        echo "No image uploaded";
        exit();
    }

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
