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
    $whereClause = "WHERE MONTH(ReviewDate) = $month AND YEAR(ReviewDate) = $year";
}
// การกรองตามวัน
elseif ($filter === "daily" && $date) {
    $safe_date = mysqli_real_escape_string($conn, $date);
    $whereClause = "WHERE DATE(ReviewDate) = '$safe_date'";
}
// การกรองตามปี
elseif ($filter === "yearly" && $year) {
    $whereClause = "WHERE YEAR(ReviewDate) = $year";
}
// การกรองตามช่วงวัน
elseif ($filter === "date-range" && $startDate && $endDate) {
    $whereClause = "WHERE DATE(ReviewDate) BETWEEN '$startDate' AND '$endDate'";
}

// ดึงข้อมูลสถานที่ที่มีการรีวิวมากที่สุด
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

// ถ้ามีข้อมูล
if ($topReviewed) {
    echo json_encode($topReviewed);
} else {
    // ถ้าไม่มีข้อมูล
    echo json_encode(["message" => "ไม่มีข้อมูล"]);
}

mysqli_close($conn); // ปิดการเชื่อมต่อฐานข้อมูล
