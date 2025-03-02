<?php
include 'db.php';

// รับค่าตัวกรองจาก URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y"); // ใช้ปีที่เลือกหรือปีปัจจุบัน
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : ''; // วันที่เริ่มต้นช่วงวัน
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : ''; // วันที่สิ้นสุดช่วงวัน

$whereClause = ""; // ค่าเริ่มต้นคือไม่มีกำหนดเงื่อนไข

// การกรองตามเดือน
if ($filter === "monthly" && $month) {
    $whereClause = "WHERE MONTH(VisitDateTime) = $month AND YEAR(VisitDateTime) = $year";
}
// การกรองตามวัน
elseif ($filter === "daily" && $date) {
    $safe_date = mysqli_real_escape_string($conn, $date);
    $whereClause = "WHERE DATE(VisitDateTime) = '$safe_date'";
}
// การกรองตามปี
elseif ($filter === "yearly" && $year) {
    $whereClause = "WHERE YEAR(VisitDateTime) = $year";
}
// การกรองตามช่วงวัน
elseif ($filter === "date-range" && $startDate && $endDate) {
    $whereClause = "WHERE DATE(VisitDateTime) BETWEEN '$startDate' AND '$endDate'";
}

// ดึงข้อมูลจำนวนการเข้าชม
$query = "SELECT SUM(VisitCount) AS total_visits FROM allvisitors $whereClause";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn)); // แสดงข้อผิดพลาดถ้าคิวรีผิดพลาด
}

$row = mysqli_fetch_assoc($result);
$totalVisits = isset($row['total_visits']) ? $row['total_visits'] : 0;

// ส่งข้อมูลในรูปแบบ JSON
$response = [
    'totalVisits' => $totalVisits
];

header('Content-Type: application/json'); // กำหนด Content-Type เป็น JSON
echo json_encode($response); // ส่งข้อมูลเป็น JSON
