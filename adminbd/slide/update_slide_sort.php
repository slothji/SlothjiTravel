<?php
include '../db.php';
if (isset($_POST['sortData'])) {
    $sortData = json_decode($_POST['sortData'], true);
    foreach ($sortData as $item) {
        $stmt = $conn->prepare("UPDATE homeslide SET HomeSort = ? WHERE HomeID = ?");
        $stmt->bind_param("ii", $item['HomeSort'], $item['HomeID']);
        $stmt->execute();
    }
    echo "success";
}
