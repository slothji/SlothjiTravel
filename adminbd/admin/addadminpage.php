<?php
session_start();
ob_start();
include '../db.php';

if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $AdminUserName = $_POST['Admin_Username'];
    $AdminPassword = $_POST['Admin_Password'];
    $AdminEmail = $_POST['Admin_Email'];

    $_SESSION['old_input'] = [
        'Admin_Username' => $AdminUserName,
        'Admin_Password' => $AdminPassword,
        'Admin_Email' => $AdminEmail
    ];

    if (!empty($AdminUserName) && !empty($AdminPassword) && !empty($AdminEmail)) {
        $sql_check = "SELECT * FROM admins WHERE AdminUserName = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $AdminUserName);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'ชื่อบัญชีนี้มีอยู่แล้วกรุณาใช้ชื่ออื่น';
        } else {
            $hashedPassword = password_hash($AdminPassword, PASSWORD_BCRYPT);
            $sql = "INSERT INTO admins (AdminUserName, AdminPassword, AdminEmail) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $AdminUserName, $hashedPassword, $AdminEmail);

            if ($stmt->execute()) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'เพิ่มข้อมูลสำเร็จ!';
                unset($_SESSION['old_input']);
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'ไม่สามารถเพิ่มข้อมูลได้';
            }
        }
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../dashboardstyle.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <title>AddPlace</title>
</head>

<body id="body-pd">
    <?php include 'sidebar.php'; ?>

    <div class="container height-100 bg-light">
        <h1>การเพิ่มข้อมูล Admin</h1>
        <form id="addAdminForm" action="addadminpage.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Admin UserName</label>
                <input type="text" class="form-control" name="Admin_Username"
                    value="<?php echo isset($_SESSION['old_input']['Admin_Username']) ? htmlspecialchars($_SESSION['old_input']['Admin_Username']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Admin Password</label>
                <div class="input-group">
                    <input type="password" id="myInput" class="form-control" name="Admin_Password" placeholder="กรุณาใส่รหัสผ่านที่ต้องการ"
                        value="<?php echo isset($_SESSION['old_input']['Admin_Password']) ? htmlspecialchars($_SESSION['old_input']['Admin_Password']) : ''; ?>" required>
                    <i class="ms-2 mt-1 fa fa-eye" id="togglePassword" style="font-size:24px" onclick="myFunction()"></i>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Email</label>

                    <input type="email" class="form-control" name="Admin_Email"
                        value="<?php echo isset($_SESSION['old_input']['Admin_Email']) ? htmlspecialchars($_SESSION['old_input']['Admin_Email']) : ''; ?>" required>
                </div>
                <button type="button" class="btn btn-primary" onclick="submitFormWithAlert()">ยืนยัน</button>
        </form>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script type="text/javascript"
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let timeout;

            function resetTimer() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    Swal.fire({
                        icon: "warning",
                        title: "หมดเวลาเชื่อมต่อ",
                        text: "กรุณา login ใหม่",
                        confirmButtonText: "ตกลง",
                    }).then(() => {
                        window.location.href = "../adminlogin/adminlogin.php";
                    });
                }, 30 * 60 * 1000);
            }

            document.addEventListener("mousemove", resetTimer);
            document.addEventListener("keypress", resetTimer);
            document.addEventListener("click", resetTimer);

            resetTimer();
        });
    </script>

    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            var icon = document.getElementById("togglePassword");
            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                x.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo $_SESSION["message"]; ?>',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'adminpage.php';
                });
                <?php unset($_SESSION['old_input']); ?>
            <?php elseif (isset($_SESSION['status']) && $_SESSION['status'] === 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?php echo $_SESSION["message"]; ?>',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>

            <?php unset($_SESSION['status'], $_SESSION['message']); ?>
        });


        function submitFormWithAlert() {
            Swal.fire({
                title: 'ยืนยันการเพิ่มข้อมูล?',
                text: "คุณต้องการเพิ่มข้อมูลนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, เพิ่มข้อมูล!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('addAdminForm').submit();
                }
            });
        }
    </script>

</body>

</html>