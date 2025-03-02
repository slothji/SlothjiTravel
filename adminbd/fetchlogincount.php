<?php
require_once "db.php"; // ไฟล์เชื่อมต่อฐานข้อมูล

// รับค่าตัวกรองจาก URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$year = date("Y"); // ใช้ปีปัจจุบัน
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : ''; // วันที่เริ่มต้นช่วงเวลา
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : ''; // วันที่สิ้นสุดช่วงเวลา

$whereClause = ""; // เริ่มต้นที่ไม่มีกำหนดเงื่อนไข

// การกรองตามปี
if ($filter === "yearly" && $year) {
    $whereClause = "AND YEAR(LoginDate) = $year";
}
// การกรองตามเดือน
elseif ($filter === "monthly" && $month) {
    $whereClause = "AND MONTH(LoginDate) = $month AND YEAR(LoginDate) = $year";
}
// การกรองตามวัน
elseif ($filter === "daily" && $date) {
    $safe_date = mysqli_real_escape_string($conn, $date);
    $whereClause = "AND DATE(LoginDate) = '$safe_date'";
}
// การกรองตามช่วงเวลา
elseif ($filter === "date-range" && $startDate && $endDate) {
    $whereClause = "AND DATE(LoginDate) BETWEEN '$startDate' AND '$endDate'";
}

// คิวรีสำหรับจำนวนการเข้าสู่ระบบ
$query = "SELECT COUNT(*) AS total_logins FROM userlogins WHERE 1 $whereClause";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$totalLogins = isset($row['total_logins']) ? $row['total_logins'] : 0;

// ส่งข้อมูลในรูปแบบ JSON
$response = [
    'totalLogins' => $totalLogins
];

header('Content-Type: application/json'); // กำหนด Content-Type เป็น JSON
echo json_encode($response); // ส่งข้อมูลเป็น JSON
