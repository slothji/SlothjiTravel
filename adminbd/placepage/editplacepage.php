<?php
session_start();
include '../db.php';
require_once 'image_upload_helper.php';
if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php");
    exit();
}

if (isset($_GET['PlaceID'])) {
    $placeID = $_GET['PlaceID'];

    $sql = "SELECT * FROM places WHERE PlaceID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $placeID);
    $stmt->execute();
    $result = $stmt->get_result();


    $glsql = "SELECT * FROM gallery WHERE PlaceID = ?";
    $glstmt = $conn->prepare($glsql);
    $glstmt->bind_param("i", $placeID);
    $glstmt->execute();
    $glresult = $glstmt->get_result();

    $galleryImages = [];
    while ($galleryRow = $glresult->fetch_assoc()) {
        $galleryImages[] = [
            'GalleryID' => $galleryRow['GalleryID'],
            'GalleryImg' => $galleryRow['GalleryImg']
        ];
    }


    if ($result->num_rows > 0) {
        $place = $result->fetch_assoc();
    } else {
        echo "<script>alert('ไม่พบข้อมูลสถานที่นี้');</script>";
        exit();
    }
} else {
    echo "<script>alert('ไม่พบ PlaceID');</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placeName = $_POST['place_name'];
    $placeTitle = $_POST['place_title'];
    $placeSubtitle = $_POST['place_subtitle'];
    $placeDetail = $_POST['place_detail'];
    $placeLocation = $_POST['place_location'];
    $placeType = $_POST['place_type'];


    if (empty($placeType)) {
        echo "<script>alert('กรุณาเลือกประเภทสถานที่');</script>";
        exit();
    }

    if (isset($_FILES['place_img']) && $_FILES['place_img']['error'] === 0) {
        $oldPlaceImg = $place['PlaceImg'];
        $oldFilePath = 'uploads/places/' . $oldPlaceImg;

        if (file_exists($oldFilePath) && $oldPlaceImg != '') {
            unlink($oldFilePath);
        }

        $uploadResult = uploadImage($_FILES['place_img'], 'uploads/places/');
        if ($uploadResult['success']) {
            $placeImg = $uploadResult['fileName'];
        } else {
            echo "<script>alert('Error uploading image: " . $uploadResult['error'] . "');</script>";
            exit();
        }
    } else {
        $placeImg = $place['PlaceImg'];
    }

    $sql = "UPDATE places SET PlaceName = ?, PlaceImg = ?, PlaceTitle = ?, PlaceSubTitle = ?, PlaceDetail = ?, PlaceLocation = ?, TypeID = ? WHERE PlaceID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $placeName, $placeImg, $placeTitle, $placeSubtitle, $placeDetail, $placeLocation, $placeType, $placeID);
    echo '<pre>';
    echo '</pre>';

    if ($stmt->execute()) {

        if (isset($_FILES['gallery_img']) && $_FILES['gallery_img']['error'][0] === 0) {
            foreach ($_FILES['gallery_img']['name'] as $index => $fileName) {
                if ($_FILES['gallery_img']['error'][$index] === 0) {
                    $file = [
                        'name' => $_FILES['gallery_img']['name'][$index],
                        'type' => $_FILES['gallery_img']['type'][$index],
                        'tmp_name' => $_FILES['gallery_img']['tmp_name'][$index],
                        'error' => $_FILES['gallery_img']['error'][$index],
                        'size' => $_FILES['gallery_img']['size'][$index]
                    ];

                    $uploadResult = uploadImage($file, 'uploads/gallerys/');
                    if ($uploadResult['success']) {
                        $galleryImg = $uploadResult['fileName'];

                        $sqlGallery = "INSERT INTO gallery (PlaceID, GalleryImg) VALUES (?, ?)";
                        $stmtGallery = $conn->prepare($sqlGallery);
                        $stmtGallery->bind_param("is", $placeID, $galleryImg);
                        $stmtGallery->execute();
                        $stmtGallery->close();
                    } else {
                        echo "<script>alert('Error uploading gallery image: " . $uploadResult['error'] . "');</script>";
                        exit();
                    }
                }
            }
        }

        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                title: "Success!",
                text: "ข้อมูลสถานที่ถูกแก้ไขเรียบร้อยแล้ว",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "adminplace.php";
            });
        </script>';
    } else {
        echo "เกิดข้อผิดพลาดในการแก้ไขข้อมูล: " . $stmt->error;
    }
}

if (isset($_GET['delete_gallery_id'])) {
    include '../db.php';

    $galleryID = $_GET['delete_gallery_id'];
    $placeID = $_GET['PlaceID'];

    $sql = "SELECT GalleryImg FROM gallery WHERE GalleryID = ? AND PlaceID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $galleryID, $placeID);
    $stmt->execute();
    $result = $stmt->get_result();
    $galleryRow = $result->fetch_assoc();

    if ($galleryRow) {
        $galleryImg = $galleryRow['GalleryImg'];
        $filePath = 'uploads/gallerys/' . $galleryImg;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $sqlDelete = "DELETE FROM gallery WHERE GalleryID = ? AND PlaceID = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("ii", $galleryID, $placeID);
        if ($stmtDelete->execute()) {
            echo "<script>alert('ลบรูปภาพสำเร็จ'); window.location.href = 'editplacepage.php?PlaceID=$placeID';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบรูปภาพ');</script>";
        }
    } else {
        echo "<script>alert('ไม่พบรูปภาพ');</script>";
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

    <title>EditPlace</title>
</head>

<body id="body-pd">
    <?php include 'sidebar.php' ?>

    <div class="container height-100 bg-light">
        <h1>การแก้ไขข้อมูล</h1>
        <form action="editplacepage.php?PlaceID=<?php echo $placeID; ?>" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="place_name" class="form-label">Place Name</label>
                <input type="text" name="place_name" class="form-control" value="<?php echo $place['PlaceName']; ?>" required>
            </div>

            <div class="mb-3">
                <img id="preview" src="uploads/places/<?php echo $place['PlaceImg']; ?>" alt="Image Preview" class="img-fluid"
                    style="max-width: 400px;">
            </div>

            <div class="mb-3">
                <label for="place_img" class="form-label">Place Image</label>
                <input type="file" name="place_img" accept="image/*" class="form-control" id="placeImg" onchange="validateImage()">
            </div>

            <div class="mb-3">
                <label for="place_title" class="form-label">Place Title</label>
                <input type="text" name="place_title" class="form-control" value="<?php echo $place['PlaceTitle']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="place_subtitle" class="form-label">Place Subtitle</label>
                <textarea name="place_subtitle" class="form-control" rows="3"><?php echo $place['PlaceSubTitle']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="place_detail" class="form-label">Place Detail</label>
                <textarea name="place_detail" class="form-control" rows="3"><?php echo $place['PlaceDetail']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="place_type" class="form-label">Type Place</label>
                <select class="form-select" name="place_type" id="place_type" required>
                    <option value="" disabled>เลือกประเภทสถานที่</option>
                    <?php foreach ($typePlaces as $type): ?>
                        <option value="<?php echo $type['TypeID']; ?>"
                            <?php echo $type['TypeID'] == $place['TypeID'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type['TypeTitle']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="mb-3">
                <iframe src="<?php echo $place['PlaceLocation']; ?>" width="600" height="450" style="border:0;" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <div class="mb-3">
                <label for="place_location" class="form-label">Place Location</label>
                <input type="text" name="place_location" class="form-control" value="<?php echo $place['PlaceLocation']; ?>">
            </div>

            <div class="mb-3">
                <label for="gallery_img" class="form-label">Gallery Images</label>
                <input type="file" name="gallery_img[]" accept="image/*" class="form-control" id="galleryImg" multiple onchange="validateMultipleFiles()">
            </div>

            <div id="gal" class="mb-3" style="display: none;">
                <label for="gallery_preview" class="form-label">Gallery Image Preview</label>
                <div id="gallery_preview" class="d-flex flex-wrap">
                </div>
            </div>

            <div class="mb-3">
                <label for="gallery_preview" class="form-label">My Gallery Image</label>
                <div class="d-flex flex-wrap">
                    <?php if (!empty($galleryImages)): ?>
                        <?php foreach ($galleryImages as $gallery): ?>
                            <img class="galleryImg" src="uploads/gallerys/<?php echo htmlspecialchars($gallery['GalleryImg']); ?>"
                                alt="Gallery Image"
                                style="width: 100px; height: 100px; object-fit: cover; margin: 5px; border: 1px solid #ddd; padding: 2px;">
                            <a href="editplacepage.php?delete_gallery_id=<?php echo $gallery['GalleryID']; ?>&PlaceID=<?php echo $placeID; ?>"
                                class="fa-solid fa-x delete-image"
                                style="margin-right: 10px; color: red; font-size: 15px; font-weight: 600; cursor: pointer;"
                                onclick="return confirm('Are you sure you want to delete this image?');"></a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No images in the gallery.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div id="myModal" class="modal">
                <span class="close">&times;</span>
                <img class="modal-content" id="img01">
                <div id="caption"></div>
            </div>

            <button type="submit" class="btn btn-primary mb-3">บันทึกการเปลี่ยนแปลง</button>
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
        document.addEventListener("DOMContentLoaded", function() {
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
        });
    </script>


    <script>
        var images = document.querySelectorAll(".galleryImg");
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        var span = document.getElementsByClassName("close")[0];

        images.forEach(function(img) {
            img.onclick = function() {
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt || "Gallery Image";
            };
        });

        span.onclick = function() {
            modal.style.display = "none";
        };
    </script>
</body>

</html>