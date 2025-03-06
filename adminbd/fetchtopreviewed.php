<?php
include 'db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y"); // ใช้ปีที่เลือกหรือปีปัจจุบัน
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : ''; // วันที่เริ่มต้นช่วงวัน
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : ''; // วันที่สิ้นสุดช่วงวัน

$whereClause = "WHERE 1";

if ($filter === "monthly" && $month) {
    $whereClause = "WHERE MONTH(ReviewDate) = $month AND YEAR(ReviewDate) = $year";
} elseif ($filter === "daily" && $date) {
    $safe_date = mysqli_real_escape_string($conn, $date);
    $whereClause = "WHERE DATE(ReviewDate) = '$safe_date'";
} elseif ($filter === "yearly" && $year) {
    $whereClause = "WHERE YEAR(ReviewDate) = $year";
} elseif ($filter === "date-range" && $startDate && $endDate) {
    $whereClause = "WHERE DATE(ReviewDate) BETWEEN '$startDate' AND '$endDate'";
}

$query = "SELECT p.PlaceTitle, p.PlaceImg, COUNT(r.ReviewID) AS total_reviews 
          FROM places p 
          JOIN reviews r ON p.PlaceID = r.PlaceID 
          $whereClause
          GROUP BY p.PlaceTitle, p.PlaceImg
          ORDER BY total_reviews DESC 
          LIMIT 5";

$result = mysqli_query($conn, $query);

$topReviewed = [];
while ($row = mysqli_fetch_assoc($result)) {
    $topReviewed[] = $row;
}


if ($topReviewed) {
    echo json_encode($topReviewed);
} else {

    echo json_encode(["message" => "ไม่มีข้อมูล"]);
}

mysqli_close($conn);
