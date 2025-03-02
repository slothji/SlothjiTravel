<?php
session_start();
include '../db.php'; // เชื่อมต่อฐานข้อมูล
if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php"); // ถ้าไม่มีการล็อกอินให้กลับไปที่หน้า login
    exit();
}
// ตรวจสอบว่ามีการส่งค่า AdminID มาหรือไม่
if (isset($_GET['AdminID'])) {
    $AdminID = $_GET['AdminID'];

    // ดึงข้อมูลปัจจุบันจากฐานข้อมูล
    $sql = "SELECT * FROM admins WHERE AdminID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $AdminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $Admin = $result->fetch_assoc(); // ดึงข้อมูลจากฐานข้อมูล
    } else {
        echo "ไม่พบข้อมูลบัญชีนี้";
        exit();
    }
} else {
    echo "ไม่พบ ID ที่ต้องการแก้ไข";
    exit();
}

// เมื่อผู้ใช้ส่งข้อมูลแก้ไข
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลที่แก้ไขจากฟอร์ม
    $AdminUserName = $_POST['admin_username'];
    $AdminPassword = $_POST['admin_password'];
    $AdminEmail = $_POST['admin_email'];

    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำกับชื่อผู้ใช้ในฐานข้อมูลหรือไม่
    $sqlCheck = "SELECT * FROM admins WHERE AdminUserName = ? AND AdminID != ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("si", $AdminUserName, $AdminID);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // ถ้าชื่อผู้ใช้ซ้ำ
        $_SESSION['username_error'] = 'duplicate'; // ตั้งค่าผลลัพธ์ว่าเจอชื่อซ้ำ
        header("Location: editadminpage.php?AdminID=" . $AdminID); // กลับไปยังหน้าแก้ไขพร้อมแจ้งเตือน
        exit();
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    if (!empty($AdminPassword)) {
        // ถ้ามีการป้อนรหัสผ่านใหม่ -> ทำการแฮชรหัสผ่าน
        $hashedPassword = password_hash($AdminPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE admins SET AdminUserName = ?, AdminPassword = ?, AdminEmail = ? WHERE AdminID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $AdminUserName, $hashedPassword, $AdminEmail, $AdminID);
    } else {
        // ถ้าไม่มีการป้อนรหัสผ่านใหม่ -> ไม่อัปเดตรหัสผ่าน
        $sql = "UPDATE admins SET AdminUserName = ?, AdminEmail = ? WHERE AdminID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $AdminUserName, $AdminEmail, $AdminID);
    }

    if ($stmt->execute()) {
        // อัปเดตสำเร็จ -> ตั้งค่า session เพื่อแจ้งผล
        $_SESSION['update_success'] = true;

        header("Location: adminpage.php"); // เปลี่ยนไปที่หน้า adminpage.php
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการแก้ไขข้อมูล: " . $stmt->error;
    }
}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <title>EditAdmin</title>


</head>

<body id="body-pd">
    <?php include 'sidebar.php' ?>

    <!-- <p class="bold"><?php echo htmlspecialchars($_SESSION['AdminUserName']); ?></p> -->
    <!--Container Main start-->
    <div class="container height-100 bg-light">
        <h1>การแก้ไขข้อมูล</h1>
        <form action="editadminpage.php?AdminID=<?php echo $AdminID; ?>" method="POST" id="editForm">
            <div class="mb-3">
                <label class="form-label">Admin UserName</label>
                <input type="text" class="form-control" name="admin_username" value="<?php echo htmlspecialchars($Admin['AdminUserName']); ?>" required>
            </div>
            <div class=" mb-3">
                <label class="form-label">Admin Password</label>
                <div class="input-group">
                    <input type="password" id="myInput" class="form-control" name="admin_password" placeholder="ใส่รหัสผ่านใหม่ที่ต้องการเปลี่ยน">
                    <i class="ms-2 mt-1 fa fa-eye" id="togglePassword" style="font-size:24px" onclick="myFunction()"></i>
                </div>

            </div>
            <div class="mb-3">
                <label class="form-label">Admin Email</label>
                <input type="email" class="form-control" name="admin_email" value="<?php echo htmlspecialchars($Admin['AdminEmail']); ?>" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="confirmEdit()">บันทึกการเปลี่ยนแปลง</button>
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
                        window.location.href = "../adminlogin/adminlogin.php"; // ล็อกเอาต์เมื่อกด "ตกลง"
                    });
                }, 30 * 60 * 1000); // 30 นาที
            }

            document.addEventListener("mousemove", resetTimer);
            document.addEventListener("keypress", resetTimer);
            document.addEventListener("click", resetTimer);

            resetTimer(); // เริ่มต้นการนับเวลา
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
        document.addEventListener("DOMContentLoaded", function(event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)

                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show-slidbar')

                        // change icon
                        toggle.classList.toggle('bx-x')
                        // add padding to body
                        bodypd.classList.toggle('body-pd')
                        // add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink))

            // Your code to run since DOM is loaded and ready
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ตรวจสอบค่าที่ส่งมาจาก PHP สำหรับชื่อผู้ใช้ซ้ำ
            <?php if (isset($_SESSION['username_error']) && $_SESSION['username_error'] == 'duplicate') { ?>
                Swal.fire({
                    icon: 'error',
                    title: 'พบชื่อผู้ใช้ซ้ำ',
                    text: 'กรุณาเปลี่ยนชื่อผู้ใช้',
                    confirmButtonText: 'ตกลง'
                });
                <?php unset($_SESSION['username_error']); // ลบ session หลังจากแสดงข้อความแล้ว 
                ?>
            <?php } else { ?>
                // ถ้าไม่มีข้อผิดพลาดชื่อซ้ำ ให้แสดงการยืนยันการแก้ไข
                document.getElementById("editForm").addEventListener("submit", function(event) {
                    event.preventDefault(); // หยุดการส่งฟอร์มก่อน
                    confirmEdit(); // เรียกฟังก์ชันการยืนยันการแก้ไข
                });
            <?php } ?>

            // ตรวจสอบผลการอัปเดตข้อมูล
            <?php if (isset($_SESSION['update_success']) && $_SESSION['update_success'] == true) { ?>
                Swal.fire({
                    icon: 'success',
                    title: 'การแก้ไขข้อมูลสำเร็จ',
                    text: 'ข้อมูลของคุณได้รับการอัปเดตแล้ว',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location.href = "adminpage.php"; // เปลี่ยนไปที่หน้า adminpage.php
                });
                <?php unset($_SESSION['update_success']); // ลบ session หลังจากแสดงข้อความแล้ว 
                ?>
            <?php } ?>
        });

        // ฟังก์ชันยืนยันการแก้ไข
        function confirmEdit() {
            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการบันทึกการเปลี่ยนแปลงนี้หรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "ใช่, บันทึกการเปลี่ยนแปลง!",
                cancelButtonText: "ไม่, ยกเลิก"
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งฟอร์มไปที่เซิร์ฟเวอร์
                    document.getElementById("editForm").submit();
                }
            });
        }
    </script>

</body>

</html>