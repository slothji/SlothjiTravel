<?php
include '../db.php';

if (!empty($_FILES['HomeImg']['name'])) {
    $fileName = time() . "_" . $_FILES['HomeImg']['name'];
    move_uploaded_file($_FILES['HomeImg']['tmp_name'], "uploadSlide/" . $fileName);

    $homeSort = $_POST['HomeSort'];

    if (empty($homeSort)) {
        $result = $conn->query("SELECT MAX(HomeSort) AS maxSort FROM homeslide");
        $row = $result->fetch_assoc();
        $homeSort = $row['maxSort'] + 1;
    }

    $stmt = $conn->prepare("INSERT INTO homeslide (HomeImg, HomeSort) VALUES (?, ?)");
    $stmt->bind_param("si", $fileName, $homeSort);
    $stmt->execute();
    echo "success";
}
