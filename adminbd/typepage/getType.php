<?php
include '../db.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM typeplace WHERE TypeID = '$id'");
echo json_encode($result->fetch_assoc());
