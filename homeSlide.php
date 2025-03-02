<?php
include 'dataDB.php'; // เชื่อมต่อฐานข้อมูล

$sql = "SELECT HomeImg, HomeStatus FROM homeslide ORDER BY HomeSort ASC";
$result = mysqli_query($conn, $sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $status = $row['HomeStatus'] === 'active' ? 'active' : '';
        echo '<div class="carousel-item ' . $status . '">';
        echo '<img src="./image/' . $row['HomeImg'] . '" class="d-block w-100" alt="Slide Image">';
        echo '</div>';
    }
} else {
    echo '<div class="carousel-item active">';
    echo '<img src="./image/default.jpg" class="d-block w-100" alt="Default Image">';
    echo '</div>';
}
