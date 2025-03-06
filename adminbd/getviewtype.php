<?php
header("Content-Type: application/json");
include "db.php";

$filter = $_GET['filter'] ?? 'all';
$month = $_GET['month'] ?? '';
$date = $_GET['date'] ?? '';

$sql = "SELECT t.TypeTitle, SUM(v.VisitCount) AS TotalVisits
        FROM visitcount v
        JOIN typeplace t ON v.TypeID = t.TypeID";

$conditions = [];

if ($filter === "monthly" && !empty($month)) {
    $year = date("Y");
    $conditions[] = "v.VisitMonth = " . intval($month) . " AND v.VisitYear = " . intval($year);
}
if ($filter === "daily" && !empty($date)) {
    $conditions[] = "v.VisitDate = '" . mysqli_real_escape_string($conn, $date) . "'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY t.TypeTitle ORDER BY TotalVisits DESC";

$result = mysqli_query($conn, $sql);

$data = ["labels" => [], "values" => []];

while ($row = mysqli_fetch_assoc($result)) {
    $data["labels"][] = $row["TypeTitle"];
    $data["values"][] = $row["TotalVisits"];
}

if (empty($data["labels"])) {
    $data["message"] = "ขณะนี้ยังไม่มีข้อมูลให้แสดง";
}

echo json_encode($data);
