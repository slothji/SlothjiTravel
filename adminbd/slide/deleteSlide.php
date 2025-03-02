<?php
include '../db.php';
if (isset($_POST['HomeID'])) {
    $id = $_POST['HomeID'];
    $stmt = $conn->prepare("SELECT HomeImg FROM homeslide WHERE HomeID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    if (file_exists("uploadSlide/" . $image)) {
        unlink("uploadSlide/" . $image);
    }

    $stmt = $conn->prepare("DELETE FROM homeslide WHERE HomeID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "success";
}
