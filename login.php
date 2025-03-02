<?php
session_start();
include 'dataDB.php';

// ตัวแปรสำหรับการตรวจสอบการล็อกอิน
$redirectUrl = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';

// หลังจากล็อกอินสำเร็จให้พาผู้ใช้ไปที่หน้าเดิม
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['UserName']);
    $password = $_POST['Passwords'];

    $sql = "SELECT * FROM users WHERE UserName = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['Passwords'])) {
            // เก็บข้อมูลในเซสชัน
            $_SESSION['UserID'] = $user['UserID'];  // เก็บ UserID
            $_SESSION['UserName'] = $user['UserName'];

            // 🔴 **บันทึกข้อมูลเข้าสู่ตาราง userlogins**
            $userID = $user['UserID'];
            $insertLog = "INSERT INTO userlogins (UserID, LoginDate) VALUES ('$userID', NOW())";
            mysqli_query($conn, $insertLog);

            // ลบค่า redirect_url หลังจากที่ใช้แล้ว
            unset($_SESSION['redirect_url']);

            // ใช้ JavaScript เพื่อพาผู้ใช้ไปที่หน้าเดิม
            echo '1';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                Swal.fire({
                    title: "Login Successful!",
                    text: "Welcome ' . $user['UserName'] . '!",
                    icon: "success"
                }).then(() => {
                    window.location.href = "' . $redirectUrl . '";
                });
            </script>';
            exit();
        } else {
            echo '2';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                Swal.fire({
                    title: "Invalid Password!",
                    text: "Please try again.",
                    icon: "error"
                }).then(() => {
                    window.history.back();
                });
            </script>';
            exit();
        }
    } else {
        echo '3';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                title: "User Not Found!",
                text: "Please check your username and try again.",
                icon: "error"
            }).then(() => {
                window.history.back();
            });
        </script>';
        exit();
    }
}
