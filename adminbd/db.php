
<?php
// ข้อมูลสำหรับการเชื่อมต่อฐานข้อมูล
$host = "localhost"; // ชื่อโฮสต์
$username = "root"; // ชื่อผู้ใช้
$password = ""; // รหัสผ่าน (ถ้าไม่มีให้เว้นว่าง)
$dbname = "slothtravel"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = mysqli_connect($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

    

