<?php
session_start();
include 'dataDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);

    if (!isset($_SESSION['verified_user'])) {
        echo "not_verified";
        exit();
    }

    $username = $_SESSION['verified_user'];

    $stmt = $conn->prepare("UPDATE users SET Passwords = ? WHERE UserName = ?");
    $stmt->bind_param("ss", $newPassword, $username);

    if ($stmt->execute()) {
        echo "success";
        session_destroy(); // ล้าง session หลังจากเปลี่ยนรหัสสำเร็จ
    } else {
        echo "error";
    }
}
