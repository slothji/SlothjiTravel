<?php
session_start();
include 'dataDB.php';

// บันทึก URL ปัจจุบันสำหรับ redirect หลังล็อกอิน
if (!isset($_SESSION['redirect_url'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}

// รับค่า PlaceID และตรวจสอบว่าถูกต้อง
$placeID = isset($_GET['PlaceID']) && is_numeric($_GET['PlaceID']) ? (int)$_GET['PlaceID'] : 0;
if ($placeID <= 0) {
    die("Invalid Place ID!");
}

$datevisitplace = date('Y-m-d H:i:s');
$visitDate = date('Y-m-d');
$visitMonth = date('m');
$visitYear = date('Y');


// ✅ อัปเดตหรือเพิ่มข้อมูลในตาราง visitcount (บันทึกการเข้าชมรายวัน)
$updateVisitCount = "INSERT INTO visitcount (PlaceID, TypeID, VisitDate, VisitMonth, VisitYear, VisitCount)
                     VALUES (?, (SELECT TypeID FROM places WHERE PlaceID = ?), ?, ?, ?, 1)
                     ON DUPLICATE KEY UPDATE VisitCount = VisitCount + 1";
$stmt = $conn->prepare($updateVisitCount);
$stmt->bind_param("iisii", $placeID, $placeID, $visitDate, $visitMonth, $visitYear);
$stmt->execute();
$stmt->close();

// ✅ ฟังก์ชันสำหรับดึงข้อมูลจากฐานข้อมูล
function fetchPlaceDetails($conn, $placeID)
{
    $query = "SELECT * FROM places WHERE PlaceID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $placeID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("No place found with the provided Place ID.");
    }

    $place = $result->fetch_assoc();
    $stmt->close();
    return $place;
}

function fetchGalleryImages($conn, $placeID)
{
    $query = "SELECT GalleryImg FROM gallery WHERE PlaceID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $placeID);
    $stmt->execute();

    $result = $stmt->get_result();
    $images = [];

    while ($row = $result->fetch_assoc()) {
        $images[] = $row['GalleryImg'];
    }

    $stmt->close();
    return $images;
}

function fetchReviews($conn, $placeID, $limit, $offset)
{
    $query = "SELECT r.Rating, r.Comment, r.ReviewDate, u.UserName, u.Emails 
              FROM reviews r
              JOIN users u ON r.UserID = u.UserID
              WHERE r.PlaceID = ?
              ORDER BY r.ReviewDate DESC
              LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $placeID, $limit, $offset);
    $stmt->execute();

    $result = $stmt->get_result();
    $reviews = [];

    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();
    return $reviews;
}

function getTotalReviews($conn, $placeID)
{
    $query = "SELECT COUNT(*) as total FROM reviews WHERE PlaceID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $placeID);
    $stmt->execute();

    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'];

    $stmt->close();
    return $total;
}

// ✅ ดึงข้อมูลสถานที่, รูปภาพ, รีวิว และจำนวนรีวิว
try {
    $reviewsPerPage = 6;
    $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max($currentPage, 1);
    $offset = ($currentPage - 1) * $reviewsPerPage;

    $place = fetchPlaceDetails($conn, $placeID);
    $images = fetchGalleryImages($conn, $placeID);
    $reviews = fetchReviews($conn, $placeID, $reviewsPerPage, $offset);
    $totalReviews = getTotalReviews($conn, $placeID);
    $totalPages = ceil($totalReviews / $reviewsPerPage);
} catch (Exception $e) {
    die($e->getMessage());
}

// ✅ ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <title><?= htmlspecialchars($place['PlaceTitle']); ?></title>
    <style>
        .carousel-indicators img {
            height: 120px;
        }
    </style>

</head>

<body>
    <!--  -->
    <?php include 'navbar.php'; ?>

    <section>
        <div class="contianer-fluid blog-banner p-0 m-0">
            <img src="./adminbd/placepage/uploads/places/<?php echo $place['PlaceImg']; ?>" data-aos="fade-in">

        </div>
    </section>

    <section class="blog">

        <div class="contianer blog-body">
            <div class="blog-card container-fuid">
                <div class="contianer blog-title">
                    <h1 data-aos="fade-up"><?= htmlspecialchars($place['PlaceTitle']); ?></h1> <!-- แสดงข้อมูลช่อง PlaceTitle ของตาราง places -->
                    <div class="row p-0 m-0">
                        <div class="col-lg-12 detail-img">
                            <div class="detail-card mx-auto">
                                <p data-aos="fade-up"><?= htmlspecialchars($place['PlaceDetail']); ?></p> <!-- แสดงข้อมูลช่อง PlaceDetail ของตาราง places -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="location">
                    <iframe src="<?= $place['PlaceLocation']; ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" data-aos="fade-up"></iframe> <!-- แสดงข้อมูลช่อง PlaceLocation ของตาราง places -->
                </div>
            </div>


            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-md-6 col-lg-10">
                        <!-- แสดง Carousel -->
                        <div class="carousel slide" id="carouselDemo" data-bs-wrap="true" data-bs-ride="carousel" data-bs-interval="3000" data-aos="fade-up">
                            <div class="carousel-inner">
                                <?php foreach ($images as $index => $image): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="./adminbd/placepage/uploads/gallerys/<?php echo htmlspecialchars($image); ?>" class="d-block w-100 gallery-img" alt="Gallery Image <?php echo $index + 1; ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button
                                class="carousel-control-prev"
                                type="button"
                                data-bs-target="#carouselDemo"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true" data-aos="fade-up"></span>
                                <span class="visually-hidden" data-aos="fade-up">Previous</span>
                            </button>
                            <button
                                class="carousel-control-next"
                                type="button"
                                data-bs-target="#carouselDemo"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true" data-aos="fade-up"></span>
                                <span class="visually-hidden" data-aos="fade-up">Next</span>
                            </button>
                            <div class="carousel-indicators" data-aos="fade-up">
                                <?php foreach ($images as $index => $image): ?>
                                    <button type="button"
                                        class="<?php echo $index === 0 ? 'active' : ''; ?>"
                                        data-bs-target="#carouselDemo"
                                        data-bs-slide-to="<?php echo $index; ?>">
                                        <img src="./adminbd/placepage/uploads/gallerys/<?php echo htmlspecialchars($image); ?>" class="d-block w-100" alt="Thumbnail <?php echo $index + 1; ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ในส่วนของ form ที่ใช้ส่งรีวิว -->
            <div class="container" data-aos="fade-up" style="margin-top: 15rem;">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6" style="justify-content: center;">
                        <div class="review-wrapper">
                            <h3>Tell us about this Place!!!</h3>
                            <form id="reviewForm">
                                <input type="hidden" name="PlaceID" value="<?= htmlspecialchars($placeID); ?>">
                                <input type="hidden" name="UserID" value="<?= isset($_SESSION['UserID']) ? htmlspecialchars($_SESSION['UserID']) : ''; ?>">

                                <div class="rating">
                                    <input type="number" name="rating" hidden>
                                    <i class="bx bx-star mystar" aria-label="1 star" style="--i: 0;"></i>
                                    <i class="bx bx-star mystar" aria-label="2 stars" style="--i: 1;"></i>
                                    <i class="bx bx-star mystar" aria-label="3 stars" style="--i: 2;"></i>
                                    <i class="bx bx-star mystar" aria-label="4 stars" style="--i: 3;"></i>
                                    <i class="bx bx-star mystar" aria-label="5 stars" style="--i: 4;"></i>
                                </div>

                                <textarea class="rating-comment" name="comment" cols="30" rows="5" placeholder="Tell us about this place" required></textarea>
                                <div class="review-btn">
                                    <button type="button" id="submitReview" class="sub-btn submit">Submit My Comment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="review-heading" data-aos="fade-up">
                <span>Comment</span>
                <h1>Review from user</h1>
            </div>

            <div id="reviewzone" class="review-box-container" data-aos="fade-up">
                <?php
                // กำหนดอาร์เรย์ของรูปภาพ
                $profileImages = [
                    "./image/profile01.png",
                    "./image/profile02.png",
                    "./image/profile03.png",
                    "./image/profile04.png"
                ];

                if (count($reviews) > 0):
                    $i = 0; // ตัวแปร index สำหรับเลือกภาพ
                ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-box">
                            <div class="rw-box-top">
                                <div class="rw-profile">
                                    <div class="rw-profile-img">
                                        <!-- ใช้ modulus (%) เพื่อเลือกภาพวนซ้ำ -->
                                        <img src="<?= $profileImages[$i % count($profileImages)]; ?>">
                                    </div>
                                    <div class="name-user">
                                        <strong><?= htmlspecialchars($review['UserName']); ?></strong>
                                        <span><?= htmlspecialchars($review['ReviewDate']); ?></span>
                                        <span><?= htmlspecialchars($review['Emails']); ?></span>
                                    </div>
                                </div>
                                <div class="reviews">
                                    <?= str_repeat('<i class="fas fa-star"></i>', $review['Rating']); ?>
                                    <?= str_repeat('<i class="far fa-star"></i>', 5 - $review['Rating']); ?>
                                </div>
                            </div>
                            <div class="user-comment">
                                <p><?= htmlspecialchars($review['Comment']); ?></p>
                            </div>
                        </div>
                        <?php $i++; // เพิ่มค่าตัวแปร index 
                        ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-reviews">
                        <p>No reviews yet. Be the first to leave a review!</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pb-3 m-0" style="justify-items: center;" data-aos="fade-up">
                <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-dark p-0 m-0">
                        <!-- <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link pev-btn" href="?PlaceID=<?= $placeID; ?>&page=<?= $currentPage - 1; ?> #reviewzone">&lt;</a>
                                <span aria-hidden="true">&gt;</span>&gt;
                            </li>
                        <?php endif; ?> -->

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                                <a class="page-link" href="?PlaceID=<?= $placeID; ?>&page=<?= $i; ?>#reviewzone"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link next-btn" href="?PlaceID=<?= $placeID; ?>&page=<?= $currentPage + 1; ?>#reviewzone">&gt;</a>
                                <span aria-hidden="true">&lt;</span>
                            </li>
                        <?php endif; ?> -->
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script type="text/javascript" src="blog.js"></script>

    <script>
        AOS.init({
            duration: 1000, // ระยะเวลาแอนิเมชันในมิลลิวินาที
            once: true // เล่นเอฟเฟกต์ครั้งเดียว
        });
    </script>

</body>

</html>