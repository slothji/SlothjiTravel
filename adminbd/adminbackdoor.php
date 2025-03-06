<?php
session_start();
include 'db.php';

if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ./adminlogin/adminlogin.php"); // ถ้าไม่มีการล็อกอินให้กลับไปที่หน้า login
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
    <link rel="stylesheet" href="dashboardstyle.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />

    <title>DashBoard</title>
</head>

<body id="body-pd">

    <?php include 'sidebar.php' ?>

    <section class="mt-4">

        <div class="container mb-0 mt-4">
            <div class="text-center mb-3" id="filterContainer" style="margin-top: 5.5rem;">
                <button class="btn btn-secondary filter-btn active mb-2" data-filter="all">รวมทั้งหมด</button>
                <button class="btn btn-secondary filter-btn mb-2" data-filter="monthly">รายเดือน</button>
                <button class="btn btn-secondary filter-btn mb-2" data-filter="daily">รายวัน</button>
                <!-- รายปี -->
                <button class="btn btn-secondary filter-btn mb-2" data-filter="yearly">รายปี</button>
                <!-- ช่วงวัน -->
                <button class="btn btn-secondary filter-btn mb-2" data-filter="date-range">ช่วงวัน</button>
            </div>

            <form id="searchForm" class="d-flex flex-column align-items-center">

                <select id="monthSelector" class="form-control mb-3" style="display: none; width: 200px;">
                    <option value="">-- เลือกเดือน --</option>
                    <?php for ($m = 1; $m <= 12; $m++) {
                        echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                    } ?>
                </select>

                <input type="date" id="dateInput" class="form-control mb-3" style="display: none; width: 200px;">

                <select id="yearSelector" class="form-control mb-3" style="display: none; width: 200px;">
                    <option value="">-- เลือกปี --</option>
                    <?php
                    $query = "SELECT DISTINCT YEAR(VisitDate) AS year FROM visitcount ORDER BY year DESC";
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $year = $row['year'];
                        echo "<option value='$year'>$year</option>";
                    }

                    mysqli_close($conn);
                    ?>
                </select>


                <div id="dateRangeContainer" class="mb-3 row align-items-center" style="display: none;">
                    <div class="col-auto">
                        <input type="date" id="startDate" class="form-control" style="width: 200px;">
                    </div>
                    <div class="col-auto">
                        <span class="input-group-text">ถึง</span>
                    </div>
                    <div class="col-auto">
                        <input type="date" id="endDate" class="form-control" style="width: 200px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100px;">ค้นหา</button>
            </form>

            <div class="row mb-5">
                <!-- จำนวนคนเข้าชมเว็บไซต์ -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card bg-light pt-3 mt-4"
                        style="width: 100%; height: 200px; align-items: center; box-shadow: 3px 3px 3px 3px rgba(0,0,0,0.3);">
                        <h4 class="text-center">มีคนเข้าชมเว็บไซต์</h4>
                        <h4 class="text-center my-5" id="visitAllCountSection" data-section="visitAllCount"
                            style="font-size: 23px; font-weight: 600;">กำลังโหลด...</h4>
                    </div>
                </div>

                <!-- จำนวนคนเข้าสู่ระบบ -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card bg-light pt-3 mt-4"
                        style="width: 100%; height: 200px; align-items: center; box-shadow: 3px 3px 3px 3px rgba(0,0,0,0.3);">
                        <h4 class="text-center">มีคนเข้าสู่ระบบไปแล้ว</h4>
                        <h4 class="text-center my-5" id="loginCountSection" data-section="loginCount"
                            style="font-size: 23px; font-weight: 600;">กำลังโหลด...</h4>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card bg-light pt-3 mt-4"
                        style="width: 100%; height: 200px; align-items: center; box-shadow: 3px 3px 3px 3px rgba(0,0,0,0.3);">
                        <h4 class="text-center">ประเภทที่มีคนเข้าชมมากที่สุด</h4>
                        <h5 class='text-center mt-4' id="visitCountSection" data-section="visitCount"
                            style="font-size: 23px; font-weight: 600;">กำลังโหลด...</h5>
                    </div>
                </div>
            </div>

            <!-- กราฟการเข้าชมแต่ละประเภท -->
            <div class="row">
                <div class="col-12">
                    <div class="card" style="box-shadow: 3px 3px 3px 3px rgba(0,0,0,0.3);">
                        <h4 class="text-center mb-3 mt-3">กราฟแสดงจำนวนการเข้าชมของสถานที่แต่ละประเภท</h4>
                        <div id="chartContainer">
                            <canvas id="visitCountChart"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="container mt-4">
            <div class="row">
                <!-- สถานที่ที่มีการเข้าชมมากที่สุด -->
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div id="topVisitedSection" class="card bg-light pt-3 px-3"
                        style="width: 100%; align-items: center; box-shadow: 3px 3px 3px rgba(0,0,0,0.3);">
                        <div class="text-center mb-3">
                            <h4 class="mt-2 mb-2">สถานที่ที่มีคนเข้าชมมากที่สุด 5 อันดับ</h4>
                        </div>
                        <table id="topVisitedTable" name="topVisitedTable" class="display table caption-top" style="width: 100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">อันดับ</th>
                                    <th class="text-center">สถานที่</th>
                                    <th class="text-center" width="25%">จำนวนการเข้าชม</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- สถานที่ที่มีการรีวิวมากที่สุด -->
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div id="topReviewedSection" class="card bg-light pt-3 px-3"
                        style="width: 100%; align-items: center; box-shadow: 3px 3px 3px rgba(0,0,0,0.3);">
                        <div class="text-center mb-3">
                            <h4 class="mt-2 mb-2">สถานที่ที่มีคนรีวิวมากที่สุด 5 อันดับ</h4>
                        </div>
                        <table id="topReviewedTable" name="topReviewedTable" class="display table caption-top" style="width: 100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">อันดับ</th>
                                    <th class="text-center">สถานที่</th>
                                    <th class="text-center" width="25%">จำนวนการเขียนรีวิว</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </section>


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
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script type="text/javascript" src="dashboardscript.js"></script>

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
                        window.location.href = "./adminlogin/adminlogout.php"; // ล็อกเอาต์เมื่อกด "ตกลง"
                    });
                }, 30 * 60 * 1000); // 30 นาที
            }

            document.addEventListener("mousemove", resetTimer);
            document.addEventListener("keypress", resetTimer);
            document.addEventListener("click", resetTimer);

            resetTimer(); // เริ่มต้นการนับเวลา
        });
    </script>

</body>

</html>