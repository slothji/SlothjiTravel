<?php
include 'dataDB.php';
if (isset($_POST['username'], $_POST['email'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $query = "SELECT * FROM users WHERE UserName = ? AND Emails = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    echo ($result->num_rows > 0) ? "found" : "not_found";
}
?>
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vender/PHPMailer-6.9.3/PHPMailer-6.9.3/src/PHPMailer.php';
// require 'vender/PHPMailer-6.9.3/PHPMailer-6.9.3/src/SMTP.php';
// require 'vender/PHPMailer-6.9.3/PHPMailer-6.9.3/src/Exception.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// $username = $_POST['UserName'];
// $email = $_POST['Emails'];

// $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
// $stmt->bind_param("ss", $username, $email);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows == 0) {
// echo "not_found";
// } else {
// // สุ่มรหัสยืนยัน 6 ตัว
// $verificationCode = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

// // บันทึกรหัสยืนยันลงใน session เพื่อตรวจสอบภายหลัง
// session_start();
// $_SESSION['verification_code'] = $verificationCode;
// $_SESSION['verified_user'] = $username; // บันทึกบัญชีที่กำลังรีเซ็ตรหัสผ่าน

// // ส่งอีเมล
// $mail = new PHPMailer(true);
// try {
// $mail->isSMTP();
// $mail->Host = 'smtp.gmail.com';
// $mail->SMTPAuth = true;
// $mail->Username = 'slothjitravel@gmail.com'; // อีเมลของคุณ
// $mail->Password = 'slothji567areslothji'; // รหัสผ่านอีเมล
// $mail->SMTPSecure = 'tls';
// $mail->Port = 587;

// $mail->setFrom('slothjitravel@gmail.com', 'SlothjiTravel');
// $mail->addAddress($email);
// $mail->Subject = 'รหัสผ่านยืนยันตัวตน';
// $mail->Body = "รหัสผ่านยืนยันตัวตนของคุณคือ: $verificationCode";

// $mail->send();
// echo "email_sent";
// } catch (Exception $e) {
// echo "error_sending_email";
// }
// }
// }