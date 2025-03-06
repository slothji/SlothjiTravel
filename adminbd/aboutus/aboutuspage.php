<?php
session_start();
include '../db.php';

if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aboutTitle = $_POST['about_title'];
    $aboutDetail = $_POST['about_detail'];
    $aboutSubTitle = $_POST['about_subtitle'];
    $aboutSubDetail = $_POST['about_subdetail'];

    $uploadDirImg = 'uploads/about_img/';
    $uploadDirProfile = 'uploads/about_profile/';

    if (!is_dir($uploadDirImg)) {
        mkdir($uploadDirImg, 0777, true);
    }
    if (!is_dir($uploadDirProfile)) {
        mkdir($uploadDirProfile, 0777, true);
    }

    $result = $conn->query("SELECT * FROM aboutus ORDER BY AboutID DESC LIMIT 1");
    $aboutData = $result->fetch_assoc();

    $aboutImg = $aboutData['AboutImg'] ?? '';
    $aboutProfile = $aboutData['AboutProfile'] ?? '';

    if (!empty($_FILES['about_img']['name'])) {

        if (!empty($aboutImg) && file_exists($uploadDirImg . $aboutImg)) {
            unlink($uploadDirImg . $aboutImg);
        }
        $aboutImgName = basename($_FILES['about_img']['name']);
        move_uploaded_file($_FILES['about_img']['tmp_name'], $uploadDirImg . $aboutImgName);
        $aboutImg = $aboutImgName;
    }

    if (!empty($_FILES['about_profile']['name'])) {

        if (!empty($aboutProfile) && file_exists($uploadDirProfile . $aboutProfile)) {
            unlink($uploadDirProfile . $aboutProfile);
        }

        $aboutProfileName = basename($_FILES['about_profile']['name']);
        move_uploaded_file($_FILES['about_profile']['tmp_name'], $uploadDirProfile . $aboutProfileName);
        $aboutProfile = $aboutProfileName;
    }

    if ($aboutData) {
        $stmt = $conn->prepare("UPDATE aboutus SET AboutImg = ?, AboutTitle = ?, AboutProfile = ?, AboutDetail = ?, AboutSubTitle = ?, AboutSubDetail = ? WHERE AboutID = ?");
        $stmt->bind_param("ssssssi", $aboutImg, $aboutTitle, $aboutProfile, $aboutDetail, $aboutSubTitle, $aboutSubDetail, $aboutData['AboutID']);
    } else {
        $stmt = $conn->prepare("INSERT INTO aboutus (AboutImg, AboutTitle, AboutProfile, AboutDetail, AboutSubTitle, AboutSubDetail) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $aboutImg, $aboutTitle, $aboutProfile, $aboutDetail, $aboutSubTitle, $aboutSubDetail);
    }

    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM aboutus ORDER BY AboutID DESC LIMIT 1");
$aboutData = $result->fetch_assoc();
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>About Us</title>
</head>

<body id="body-pd">

    <?php include 'sidebar.php' ?>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mt-3">การข้อมูลเกี่ยวกับเรา</h1>
                    <form id="AboutForm" action="aboutuspage.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <?php if (!empty($aboutData['AboutImg'])): ?>
                                <img src="./uploads/about_img/<?= $aboutData['AboutImg'] ?>" alt="About Image" class="img-fluid mt-2" style="max-width: 200px;">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="aboutImg" class="form-label">About Image</label>
                            <input type="file" name="about_img" accept="image/*" class="form-control" id="aboutImg">
                        </div>
                        <div class="mb-3">
                            <label for="aboutTitle" class="form-label">About Title</label>
                            <input type="text" class="form-control" name="about_title" id="aboutTitle" value="<?= $aboutData['AboutTitle'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <?php if (!empty($aboutData['AboutProfile'])): ?>
                                <img src="./uploads/about_profile/<?= $aboutData['AboutProfile'] ?>" alt="About Profile" class="img-fluid mt-2" style="max-width: 200px;">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="aboutProfile" class="form-label">About Profile</label>
                            <input type="file" name="about_profile" accept="image/*" class="form-control" id="aboutProfile">
                        </div>
                        <div class="mb-3">
                            <label for="aboutDetail" class="form-label">About Detail</label>
                            <textarea class="form-control" name="about_detail" id="aboutDetail" rows="3"><?= $aboutData['AboutDetail'] ?? '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="aboutSubTitle" class="form-label">About SubTitle</label>
                            <input type="text" class="form-control" name="about_subtitle" id="aboutSubTitle" value="<?= $aboutData['AboutSubTitle'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="aboutSubDetail" class="form-label">About SubDetail</label>
                            <textarea class="form-control" name="about_subdetail" id="aboutSubDetail" rows="3"><?= $aboutData['AboutSubDetail'] ?? '' ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    </form>
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
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

                        nav.classList.toggle('show-slidbar')
                        toggle.classList.toggle('bx-x')
                        bodypd.classList.toggle('body-pd')
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }
            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink))
        });
    </script>
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


</body>

</html>