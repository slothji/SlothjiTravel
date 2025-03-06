<?php
include 'db.php';

$filter = $_GET['filter'] ?? 'all';
$month = $_GET['month'] ?? '';
$date = $_GET['date'] ?? '';

$query = "SELECT TypeID, SUM(VisitCount) as TotalVisits FROM placevisits WHERE 1";

if ($filter === "monthly" && $month) {
    $query .= " AND MONTH(LastVisited) = $month";
} elseif ($filter === "daily" && $date) {
    $query .= " AND DATE(LastVisited) = '$date'";
}

$query .= " GROUP BY TypeID ORDER BY TotalVisits DESC";

$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
