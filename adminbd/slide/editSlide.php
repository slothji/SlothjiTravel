<?php
include '../db.php';
$homeID = $_POST['HomeID'];
$oldImage = "";

$stmt = $conn->prepare("SELECT HomeImg FROM homeslide WHERE HomeID = ?");
$stmt->bind_param("i", $homeID);
$stmt->execute();
$stmt->bind_result($oldImage);
$stmt->fetch();
$stmt->close();

if ($_FILES['HomeImg']['name']) {
    $newImage = time() . "_" . $_FILES['HomeImg']['name'];
    move_uploaded_file($_FILES['HomeImg']['tmp_name'], "uploadSlide/" . $newImage);

    if (file_exists("uploadSlide/" . $oldImage)) {
        unlink("uploadSlide/" . $oldImage);
    }

    $stmt = $conn->prepare("UPDATE homeslide SET HomeImg = ? WHERE HomeID = ?");
    $stmt->bind_param("si", $newImage, $homeID);
} else {
    $stmt = $conn->prepare("UPDATE homeslide SET HomeImg = HomeImg WHERE HomeID = ?");
    $stmt->bind_param("i", $homeID);
}

$stmt->execute();
echo "success";
