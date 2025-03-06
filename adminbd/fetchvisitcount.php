<?php
header('Content-Type: application/json; charset=utf-8');
require_once('db.php');

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
} else {
    $whereConditions[] = "VisitYear = YEAR(CURRENT_DATE)";
}

$whereClause = empty($whereConditions) ? "1=1" : implode(" AND ", $whereConditions);

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

$response = [];

if ($result) {
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        $response = [
            'type' => $data['TypeTitle'],
            'visitCount' => $data['TotalVisitCount']
        ];
    } else {
        $response = [
            'type' => 'ไม่มีข้อมูล',
            'visitCount' => 0
        ];
    }
} else {
    $response = [
        'error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล'
    ];
}

echo json_encode($response);

mysqli_close($conn);
