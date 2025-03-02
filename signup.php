<?php
include 'dataDB.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['UserName']);
    $email = trim($_POST['Emails']);
    $password = password_hash($_POST['Passwords'], PASSWORD_DEFAULT);

    // ตรวจสอบว่า Google reCAPTCHA ได้ถูกเลือกหรือไม่
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $secretKey = "6LeR9eQqAAAAACW2Hk0hZ9OOhV5ESp3qzQXGCl_F"; // ใส่ Secret Key ที่ได้จาก Google reCAPTCHA

        // ส่งคำขอไปตรวจสอบกับ Google
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captchaSuccess = json_decode($verify);

        // ตรวจสอบว่า reCAPTCHA ผ่านหรือไม่
        if ($captchaSuccess->success) {
            // เช็คว่าชื่อผู้ใช้หรืออีเมลซ้ำหรือไม่
            $checkSql = "SELECT * FROM users WHERE UserName = ? ";
            $stmtCheck = $conn->prepare($checkSql);
            $stmtCheck->bind_param("s", $username);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();

            if ($result->num_rows > 0) {
                // พบว่ามีข้อมูลซ้ำ
                echo '1';
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo "<script>
                    Swal.fire({
                        title: 'ชื่อผู้ใช้หรืออีเมลซ้ำ!',
                        text: 'กรุณาใช้ชื่อผู้ใช้หรืออีเมลอื่น',
                        icon: 'warning',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.history.back();
                    });
                </script>";
            } else {
                // ไม่พบข้อมูลซ้ำ สามารถสมัครได้
                $stmtCheck->close();

                $sql = "INSERT INTO users (UserName, Emails, Passwords) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $password);

                if ($stmt->execute()) {
                    echo '2';
                    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                    echo "<script>
                        Swal.fire({
                            title: 'สมัครสมาชิกสำเร็จ!',
                            text: 'ระบบกำลังนำคุณไปยังหน้าหลัก สามารถเข้าสู่ระบบได้',
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            window.location = 'index.php';
                        });
                    </script>";
                } else {
                    echo '3';
                    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                    echo "<script>
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'กรุณาลองอีกครั้ง',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    </script>";
                }
                $stmt->close();
            }
        } else {
            // reCAPTCHA ไม่ผ่าน
            echo '4';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                Swal.fire({
                    title: 'reCAPTCHA ไม่ถูกต้อง!',
                    text: 'โปรดลองอีกครั้ง',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        }
    } else {
        echo '5';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo "<script>
            Swal.fire({
                title: 'กรุณายืนยัน reCAPTCHA!',
                text: 'คุณต้องยืนยันว่าคุณไม่ใช่บอท',
                icon: 'warning',
                confirmButtonText: 'ตกลง'
            });
        </script>";
    }
}
