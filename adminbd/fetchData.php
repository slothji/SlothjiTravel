<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$year = date("Y");

$whereClause = "";
if ($filter === "monthly" && $month) {
    $whereClause = "AND VisitMonth = $month AND VisitYear = $year";
} elseif ($filter === "daily" && $date) {
    $whereClause = "AND VisitDate = '" . mysqli_real_escape_string($conn, $date) . "'";
}

// จำนวนคนเข้าชมเว็บไซต์
$sqlVisits = "SELECT SUM(VisitCount) AS total_visits FROM allvisitors WHERE 1 $whereClause";
$resultVisits = $conn->query($sqlVisits);
$totalVisits = ($resultVisits->fetch_assoc())['total_visits'] ?? 0;

// จำนวนคนเข้าสู่ระบบ
$sqlLogins = "SELECT COUNT(*) AS total_logins FROM user_logins WHERE 1 $whereClause";
$resultLogins = $conn->query($sqlLogins);
$totalLogins = ($resultLogins->fetch_assoc())['total_logins'] ?? 0;

// ประเภทที่มีคนเข้าชมมากที่สุด
$sqlTopType = "SELECT t.TypeTitle, SUM(v.VisitCount) AS TotalVisits
               FROM visitcount v JOIN typeplace t ON v.TypeID = t.TypeID
               WHERE 1 $whereClause
               GROUP BY v.TypeID, t.TypeTitle
               ORDER BY TotalVisits DESC LIMIT 1";
$resultTopType = $conn->query($sqlTopType);
$topType = $resultTopType->fetch_assoc() ?: ["TypeTitle" => "ไม่มีข้อมูล", "TotalVisits" => 0];

// ส่งข้อมูลกลับไปเป็น JSON
echo json_encode([
    "visitCount" => $totalVisits,
    "loginCount" => $totalLogins,
    "topVisited" => [
        "type" => $topType["TypeTitle"],
        "visits" => $topType["TotalVisits"]
    ]
]);
