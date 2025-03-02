<?php
header('Content-Type: application/json; charset=utf-8');
require_once('db.php');

// ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
if (!$conn) {
    echo json_encode(["error" => "ไม่สามารถเชื่อมต่อกับฐานข้อมูล"]);
    exit();
}

// รับค่าตัวกรองจาก URL
$filter = $_GET['filter'] ?? 'all';
$month = $_GET['month'] ?? null;
$date = $_GET['date'] ?? null;
$year = $_GET['year'] ?? null;
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

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
    // กรองข้อมูลทั้งหมด (ไม่มีเงื่อนไขพิเศษ)
} else {
    $whereConditions[] = "VisitYear = YEAR(CURRENT_DATE)";
}

$whereClause = empty($whereConditions) ? "1=1" : implode(" AND ", $whereConditions);

// ตรวจสอบ SQL Query ก่อนรัน
$sql = "
    SELECT tp.TypeID, tp.TypeTitle, SUM(vc.VisitCount) AS TotalVisitCount
    FROM visitcount vc
    JOIN typeplace tp ON vc.TypeID = tp.TypeID
    WHERE $whereClause
    GROUP BY tp.TypeID, tp.TypeTitle
    ORDER BY tp.TypeID
";

// ดึงข้อมูลจากฐานข้อมูล
$result = mysqli_query($conn, $sql);

$visitData = [];

if ($result) {
    while ($data = mysqli_fetch_assoc($result)) {
        $visitData[] = [
            'TypeTitle' => $data['TypeTitle'],
            'TotalVisitCount' => (int)$data['TotalVisitCount']
        ];
    }
} else {
    // ถ้ามีข้อผิดพลาดในการดึงข้อมูล
    echo json_encode(["error" => "เกิดข้อผิดพลาดในการดึงข้อมูล SQL"]);
    exit();
}

// ส่งข้อมูลกลับเป็น JSON
echo json_encode($visitData);

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
