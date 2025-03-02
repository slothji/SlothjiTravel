<?php
// เชื่อมต่อฐานข้อมูล
header('Content-Type: application/json; charset=utf-8'); // เปลี่ยนเป็น application/json
require_once('db.php');

// รับค่าตัวกรองจาก URL
$filter = $_GET['filter'] ?? 'all'; // ค่า filter ที่ส่งมา
$month = $_GET['month'] ?? null; // ค่าเดือนที่กรอง
$date = $_GET['date'] ?? null; // ค่าวันที่ที่กรอง
$year = $_GET['year'] ?? null; // ปีที่กรอง
$startDate = $_GET['startDate'] ?? null; // วันที่เริ่มต้นช่วงวัน
$endDate = $_GET['endDate'] ?? null; // วันที่สิ้นสุดช่วงวัน

// สร้างเงื่อนไขสำหรับ SQL Query
$whereConditions = [];

if ($filter === 'monthly' && $month) {
    $whereConditions[] = "VisitMonth = $month";
}
if ($filter === 'daily' && $date) {
    $whereConditions[] = "VisitDate = '$date'";
}
if ($filter === 'yearly' && $year) {
    $whereConditions[] = "VisitYear = $year";
}
if ($filter === 'date-range' && $startDate && $endDate) {
    $whereConditions[] = "VisitDate BETWEEN '$startDate' AND '$endDate'";
}

if ($filter === 'all') {
    // กรองข้อมูลทั้งหมด
} else {
    // เพิ่มเงื่อนไขการกรองโดยใช้ปีปัจจุบัน
    $whereConditions[] = "VisitYear = YEAR(CURRENT_DATE)";
}

// ถ้าไม่มีเงื่อนไข, ให้ใช้เงื่อนไข 1=1
$whereClause = empty($whereConditions) ? "1=1" : implode(" AND ", $whereConditions);

// Query ที่ใช้ดึงข้อมูลประเภทที่มีการเข้าชมมากที่สุด
$sql = "
    SELECT tp.TypeID, tp.TypeTitle, SUM(vc.VisitCount) AS TotalVisitCount
    FROM visitcount vc
    JOIN typeplace tp ON vc.TypeID = tp.TypeID
    WHERE $whereClause
    GROUP BY tp.TypeID, tp.TypeTitle
    ORDER BY TotalVisitCount DESC
    LIMIT 1
";

$result = mysqli_query($conn, $sql);

$response = []; // เก็บข้อมูลที่จะส่งกลับ

if ($result) {
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        // ส่งข้อมูลในรูปแบบ JSON
        $response = [
            'type' => $data['TypeTitle'],
            'visitCount' => $data['TotalVisitCount']
        ];
    } else {
        // ถ้าไม่มีข้อมูล
        $response = [
            'type' => 'ไม่มีข้อมูล',
            'visitCount' => 0
        ];
    }
} else {
    // ถ้ามีข้อผิดพลาดใน query
    $response = [
        'error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล'
    ];
}

// ส่งข้อมูลกลับเป็น JSON
echo json_encode($response);

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
