<?php
include 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// รับค่าตัวกรองจาก URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y"); // ใช้ปีที่เลือกหรือปีปัจจุบัน
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : ''; // วันที่เริ่มต้นช่วงวัน
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : ''; // วันที่สิ้นสุดช่วงวัน

$whereClause = "WHERE 1"; // ค่าเริ่มต้นให้แสดงข้อมูลทั้งหมด

// การกรองตามเดือน
if ($filter === "monthly" && $month) {
    $whereClause = "WHERE MONTH(VisitDate) = $month AND YEAR(VisitDate) = $year";
}
// การกรองตามวัน
elseif ($filter === "daily" && $date) {
    $safe_date = mysqli_real_escape_string($conn, $date);
    $whereClause = "WHERE DATE(VisitDate) = '$safe_date'";
}
// การกรองตามปี
elseif ($filter === "yearly" && $year) {
    $whereClause = "WHERE YEAR(VisitDate) = $year";
}
// การกรองตามช่วงวัน
elseif ($filter === "date-range" && $startDate && $endDate) {
    $whereClause = "WHERE DATE(VisitDate) BETWEEN '$startDate' AND '$endDate'";
}

// ดึงข้อมูลสถานที่ที่ได้รับการเข้าชมมากที่สุด พร้อมรูปภาพ
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

// ถ้ามีข้อมูล
if ($topVisited) {
    echo json_encode($topVisited);
} else {
    // ถ้าไม่มีข้อมูล
    echo json_encode(["message" => "ไม่มีข้อมูล"]);
}

mysqli_close($conn); // ปิดการเชื่อมต่อฐานข้อมูล
