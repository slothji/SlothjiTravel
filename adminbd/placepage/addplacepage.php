<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php';
require_once 'image_upload_helper.php';
if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placeName = $_POST['place_name'];
    $placeTitle = $_POST['place_title'];
    $placeSubtitle = $_POST['place_subtitle'];
    $placeDetail = $_POST['place_detail'];
    $placeLocation = $_POST['place_location'];
    $placeType = $_POST['place_type'];

    if (!empty($placeName) && !empty($placeTitle) && isset($_FILES['place_img']) && $_FILES['place_img']['error'] === 0) {
        $uploadResult = uploadImage($_FILES['place_img'], 'uploads/places/');

        if ($uploadResult['success']) {
            $placeImg = $uploadResult['fileName'];

            $sql = "INSERT INTO places (PlaceName, PlaceImg, PlaceTitle, PlaceSubTitle, PlaceDetail, PlaceLocation, typeID) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $placeName, $placeImg, $placeTitle, $placeSubtitle, $placeDetail, $placeLocation, $placeType);

            if (empty($placeType)) {
                echo "<script>alert('กรุณาเลือกประเภทสถานที่');</script>";
                return;
            }

            if ($stmt->execute()) {
                $placeID = $stmt->insert_id;
                $stmt->close();

                $result = $conn->query("SELECT MAX(PlaceNumbers) AS max_number FROM places");
                $row = $result->fetch_assoc();
                $newPlaceNumber = $row['max_number'] + 1;

                $updatePlaceNumberSQL = "UPDATE places SET PlaceNumbers = ? WHERE PlaceID = ?";
                $updateStmt = $conn->prepare($updatePlaceNumberSQL);
                $updateStmt->bind_param("ii", $newPlaceNumber, $placeID);
                $updateStmt->execute();
                $updateStmt->close();

                if (isset($_FILES['MultiUpsload']) && count($_FILES['MultiUpsload']['name']) > 0) {
                    $galleryUploads = $_FILES['MultiUpsload'];
                    $galleryPath = 'uploads/gallerys/';

                    for ($i = 0; $i < count($galleryUploads['name']); $i++) {
                        error_log("Processing file $i: " . $galleryUploads['name'][$i]);

                        if ($galleryUploads['error'][$i] === 0) {
                            $fileData = [
                                'name' => $galleryUploads['name'][$i],
                                'type' => $galleryUploads['type'][$i],
                                'tmp_name' => $galleryUploads['tmp_name'][$i],
                                'error' => $galleryUploads['error'][$i],
                                'size' => $galleryUploads['size'][$i],
                            ];

                            $uploadResult = uploadImage($fileData, $galleryPath);

                            if ($uploadResult['success']) {
                                error_log("File uploaded successfully: " . $uploadResult['fileName']);
                                $galleryImg = $uploadResult['fileName'];

                                $sqlGallery = "INSERT INTO gallery (GalleryImg, PlaceID) VALUES (?, ?)";
                                $stmtGallery = $conn->prepare($sqlGallery);
                                $stmtGallery->bind_param("si", $galleryImg, $placeID);

                                if ($stmtGallery->execute()) {
                                    error_log("File saved to database: $galleryImg");
                                } else {
                                    error_log("Database error: " . $stmtGallery->error);
                                }

                                $stmtGallery->close();
                            } else {
                                error_log("Upload failed: " . $uploadResult['error']);
                            }
                        } else {
                            error_log("File $i skipped due to error: " . $galleryUploads['error'][$i]);
                        }
                    }
                }

                // แสดงผลสำเร็จ
                echo '1';
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
                    Swal.fire({
                        title: "สำเร็จ!",
                        text: "ทำการเพิ่มข้อมูลสถานที่เรียบร้อยแล้ว",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.location.href = "adminplace.php";
                    });
                </script>';
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "<script>alert('Error uploading image: " . $uploadResult['error'] . "');</script>";
        }
    } else {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบทุกช่อง');</script>";
    }
}
$typePlaceSql = "SELECT * FROM typeplace";
$typePlaceStmt = $conn->prepare($typePlaceSql);
$typePlaceStmt->execute();
$typePlaceResult = $typePlaceStmt->get_result();
$typePlaces = [];

while ($row = $typePlaceResult->fetch_assoc()) {
    $typePlaces[] = $row;
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
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <title>AddPlace</title>
</head>

<body id="body-pd">
    <?php include 'sidebar.php' ?>

    <div class="container height-100 bg-light">
        <h1>การเพิ่มข้อมูลสถานที่</h1>
        <form action="addplacepage.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Place Name</label>
                <input type="text" name="place_name" class="form-control" id="exampleFormControlInput1" placeholder="Place Name" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Place Image</label>
                <input type="file" name="place_img" accept="image/*" class="form-control" id="placeImg" required onchange="validateImage()">
            </div>
            <div class="mb-3">
                <label for="preview" class="form-label">Image Preview</label>
                <img id="preview" src="#" alt="Image Preview" class="img-fluid" style="display: none; max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Place Title</label>
                <input type="text" name="place_title" class="form-control" id="exampleFormControlInput1" placeholder="Title" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Place Subtitle</label>
                <textarea class="form-control" name="place_subtitle" id="exampleFormControlTextarea1" rows="3" placeholder="Subtitle"></textarea>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Place Detail</label>
                <textarea class="form-control" name="place_detail" id="exampleFormControlTextarea1" rows="3" placeholder="Detail"></textarea>
            </div>
            <div class="mb-3">
                <label for="place_type" class="form-label">Type Place</label>
                <select class="form-select" name="place_type" id="place_type" required>
                    <option value="" disabled selected>เลือกประเภทสถานที่</option>
                    <?php foreach ($typePlaces as $type): ?>
                        <option value="<?php echo $type['TypeID']; ?>">
                            <?php echo htmlspecialchars($type['TypeTitle']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Place Location</label>
                <input type="text" name="place_location" class="form-control" id="exampleFormControlInput1" placeholder="Location">
            </div>
            <div class="mb-3">
                <label for="formFileMultiple" class="form-label">Multiple files Images Gallery</label>
                <input class="form-control" type="file" id="formFileMultiple" name="MultiUpsload[]" multiple onchange="validateMultipleFiles()">
            </div>

            <button type="submit" class="btn btn-primary">ยืนยัน</button>
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
        function validateImage() {
            const fileInput = document.getElementById("placeImg");
            const file = fileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/png'];

            if (file && !allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'ชนิดไฟล์ไม่ถูกต้อง',
                    text: 'โปรดเลือกไฟล์ .jpg หรือ .png เท่านั้น',
                });
                fileInput.value = '';
            }
        }
    </script>

    <script>
        function validateMultipleFiles() {
            const fileInput = document.getElementById("formFileMultiple");
            const files = fileInput.files;
            const allowedTypes = ['image/jpeg', 'image/png'];

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ชนิดไฟล์ไม่ถูกต้อง',
                        text: 'โปรดเลือกไฟล์ .jpg หรือ .png เท่านั้น',
                    });
                    fileInput.value = '';
                    return;
                }
            }
        }
    </script>

    <script>
        document.getElementById('placeImg').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.style.width = '300px';
                    preview.style.height = '300px';
                    preview.style.objectFit = 'cover';
                };

                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        });

        document.getElementById('formFileMultiple').addEventListener('change', function(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('previewContainer');

            previewContainer.innerHTML = '';

            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    if (file) {
                        const reader = new FileReader();

                        const img = document.createElement('img');
                        img.style.width = '150px';
                        img.style.height = '150px';
                        img.style.margin = '5px';
                        img.style.objectFit = 'cover';
                        img.style.border = '1px solid #ddd';
                        img.style.padding = '5px';

                        reader.onload = function(e) {
                            img.src = e.target.result;
                            previewContainer.appendChild(img);
                        };

                        reader.readAsDataURL(file);
                    }
                });
            }
        });
        document.getElementById('galleryImg').addEventListener('change', function(event) {
            $('#gal').show();
            const files = event.target.files;
            const previewContainer = document.getElementById('gallery_preview');
            previewContainer.innerHTML = '';

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.style.margin = '5px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)

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

</body>

</html>