<?php
session_start();
header("Content-Type: text/plain");

if (!isset($_SESSION['verified_user'])) {
    echo "not_verified";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dataDB.php';

    $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
    $username = $_SESSION['verified_user'];

    $stmt = $conn->prepare("UPDATE users SET Passwords = ? WHERE UserName = ?");
    $stmt->bind_param("ss", $newPassword, $username);

    if ($stmt->execute()) {
        unset($_SESSION['verified_user']); // ลบ session หลังจากเปลี่ยนรหัสสำเร็จ
        echo "success"; // ✅ ส่งค่า "success" เท่านั้น
    } else {
        echo "error";
    }
}
