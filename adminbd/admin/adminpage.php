<?php
session_start();
include '../db.php';
if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php"); // ถ้าไม่มีการล็อกอินให้กลับไปที่หน้า login
    exit();
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <title>DashBoard</title>
</head>

<body id="body-pd">

    <?php include 'sidebar.php' ?>
    <div class="main-content">
        <div class="container place-table">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body mt-3">
                        <div class="table-responsive typetable">
                            <table id="admins" name="admins" class="display table caption-top" style="width: 100%;">
                                <caption>List of Admin</caption>
                                <button class="btn btn-success btn-sm edit-btn" data-id="">
                                    <a href="addadminpage.php">
                                        <i class="fa-solid fa-plus"></i> <span class="add-text">เพิ่ม</span>
                                    </a>
                                </button>
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" width="10%">AdminID</th>
                                        <th class="text-center">AdminUserName</th>
                                        <!-- <th>AdminPassword</th> -->
                                        <th class="text-center">AdminEmail</th>
                                        <th class="text-center" width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $conn->query("SELECT * FROM admins Where AdminID != '1'");
                                    if (!$stmt) {
                                        die("Error: " . $conn->error);
                                    }
                                    // You don't need to call execute() here
                                    $Admins = $stmt->fetch_all(MYSQLI_ASSOC); // Use fetch_all for an array of results
                                    $num = 1;
                                    foreach ($Admins as $admin) {

                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $num; ?></td>
                                            <td class=""><?php echo htmlspecialchars($admin['AdminUserName']); ?></td>
                                            <!-- <td><?php echo htmlspecialchars($admin['AdminPassword']); ?></td> -->
                                            <td class=""><?php echo htmlspecialchars($admin['AdminEmail']); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm edit-btn" data-id="1" <?php echo $admin['AdminID']; ?>" data-bs-target="#editModal">
                                                    <a href="editadminpage.php?AdminID=<?php echo $admin['AdminID']; ?>">
                                                        <i class="fa-solid fa-pen" style="color: #fff;"></i>
                                                    </a>
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $admin['AdminID']; ?>">
                                                    <i class="fa-solid fa-trash" style="color: #fff;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
                                        $num++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
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
                }, 30 * 60 * 1000); // 30 นาที
            }

            document.addEventListener("mousemove", resetTimer);
            document.addEventListener("keypress", resetTimer);
            document.addEventListener("click", resetTimer);

            resetTimer(); // เริ่มต้นการนับเวลา
        });
    </script>
    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {

            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script>
        let table = new DataTable('#admins');
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
        $(".delete-btn").click(function() {
            console.log("ปุ่มลบถูกคลิก");

            var adminID = $(this).data("id");
            console.log("AdminID ที่จะลบ: ", adminID);

            Swal.fire({
                title: "คุณแน่ใจหรือไม่",
                text: "คุณต้องการที่จะลบบัญชีนี้หรือไม่",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "ใช่! ต้องการ",
                cancelButtonText: "ไม่! ยกเลิก"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'deleteadmin.php', // ตรวจสอบให้แน่ใจว่าไฟล์นี้อยู่ในที่ถูกต้อง
                        type: 'POST',
                        data: {
                            AdminID: adminID
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log("Response จากเซิร์ฟเวอร์: ", response);

                            if (response.status === "success") {
                                Swal.fire({
                                    title: 'ลบสำเร็จ!',
                                    text: response.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'ข้อผิดพลาดของเซิร์ฟเวอร์!',
                                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>

    <?php if (isset($_SESSION['update_success']) && $_SESSION['update_success'] == true) {
        echo "<script>
        Swal.fire({
            icon: 'success',
       title: 'การแก้ไขข้อมูลสำเร็จ',
        text: 'ข้อมูลของคุณได้รับการอัปเดตแล้ว',
            confirmButtonColor: '#198754',
        })
    </script>";
    }
    unset($_SESSION['update_success']); // ลบ session หลังจากแสดงข้อความแล้ว 

    ?>
    <!-- <?php if ($this->session->flashdata('result') == 'false') {
                echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'ไม่สำเร็จ',
            text: '" . $this->session->flashdata('message') . "',
            confirmButtonColor: '#198754',
        })
    </script>";
            } ?> -->

</body>

</html>