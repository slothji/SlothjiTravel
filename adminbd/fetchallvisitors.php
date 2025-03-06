<?php
include 'db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

$whereClause = "";

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

$query = "SELECT SUM(VisitCount) AS total_visits FROM allvisitors $whereClause";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$totalVisits = isset($row['total_visits']) ? $row['total_visits'] : 0;

$response = [
    'totalVisits' => $totalVisits
];

header('Content-Type: application/json');
echo json_encode($response);
