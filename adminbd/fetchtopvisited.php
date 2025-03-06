<?php
include 'db.php';

// รับค่าตัวกรองจาก URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y"); // ใช้ปีที่เลือกหรือปีปัจจุบัน
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : ''; // วันที่เริ่มต้นช่วงวัน
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : ''; // วันที่สิ้นสุดช่วงวัน

$whereClause = "WHERE 1";


if ($filter === "monthly" && $month) {
    $whereClause = "WHERE MONTH(VisitDate) = $month AND YEAR(VisitDate) = $year";
} elseif ($filter === "daily" && $date) {
    $safe_date = mysqli_real_escape_string($conn, $date);
    $whereClause = "WHERE DATE(VisitDate) = '$safe_date'";
} elseif ($filter === "yearly" && $year) {
    $whereClause = "WHERE YEAR(VisitDate) = $year";
} elseif ($filter === "date-range" && $startDate && $endDate) {
    $whereClause = "WHERE DATE(VisitDate) BETWEEN '$startDate' AND '$endDate'";
}

$query = "SELECT p.PlaceTitle, p.PlaceImg, SUM(vc.VisitCount) AS total_visits 
          FROM places p 
          JOIN visitcount vc ON p.PlaceID = vc.PlaceID 
          $whereClause
          GROUP BY p.PlaceTitle, p.PlaceImg
          ORDER BY total_visits DESC 
          LIMIT 5";
$result = mysqli_query($conn, $query);

$topVisited = [];
while ($row = mysqli_fetch_assoc($result)) {
    $topVisited[] = $row;
}

if ($topVisited) {
    echo json_encode($topVisited);
} else {

    echo json_encode(["message" => "ไม่มีข้อมูล"]);
}

mysqli_close($conn);
