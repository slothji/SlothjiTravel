<?php
session_start();
session_unset();
session_destroy(); // ลบข้อมูลทั้งหมดใน Session
header("Location: adminlogin.php"); // กลับไปยังหน้า Login
exit();
