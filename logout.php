<?php
session_start();
session_destroy(); // ทำลาย Session ทั้งหมด
header("Location: index.php"); // กลับไปหน้าแรก
exit();
