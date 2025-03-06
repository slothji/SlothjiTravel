<?php
include 'dataDB.php';
session_start();
// เก็บ URL ปัจจุบันใน session ก่อนที่จะแสดงฟอร์มล็อกอิน
if (!isset($_SESSION['redirect_url'])) {
  $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}
// ระบุชื่อของหน้า
$page_name = "homepage";
$datevisit = date('Y-m-d H:i:s'); // วันที่และเวลาปัจจุบัน
$visit_date = date('Y-m-d'); // ใช้เป็นตัวแยกข้อมูลรายวัน

// ตรวจสอบว่ามีข้อมูลของวันนี้แล้วหรือไม่
$count_sql = "SELECT VisitCount FROM allvisitors WHERE PageName = ? AND DATE(VisitDateTime) = ?";
$stmt = $conn->prepare($count_sql);
$stmt->bind_param("ss", $page_name, $visit_date);
$stmt->execute();
$visitresult = $stmt->get_result();

if ($visitresult->num_rows > 0) {
  // ถ้ามีข้อมูลวันนี้แล้ว ให้เพิ่มจำนวนการเข้าชม
  $update_sql = "UPDATE allvisitors SET VisitCount = VisitCount + 1, VisitDateTime = ? 
                   WHERE PageName = ? AND DATE(VisitDateTime) = ?";
  $update_stmt = $conn->prepare($update_sql);
  $update_stmt->bind_param("sss", $datevisit, $page_name, $visit_date);
  $update_stmt->execute();
  $update_stmt->close();
} else {
  // ถ้ายังไม่มี ให้เพิ่มข้อมูลใหม่
  $insert_sql = "INSERT INTO allvisitors (PageName, VisitCount, VisitDateTime) VALUES (?, 1, ?)";
  $insert_stmt = $conn->prepare($insert_sql);
  $insert_stmt->bind_param("ss", $page_name, $datevisit);
  $insert_stmt->execute();
  $insert_stmt->close();
}

$stmt->close();


$topsql = "
    SELECT places.PlaceID, places.PlaceTitle, places.PlaceSubTitle, places.PlaceImg, 
           AVG(reviews.Rating) AS avgRating
    FROM places
    LEFT JOIN reviews ON places.PlaceID = reviews.PlaceID
    GROUP BY places.PlaceID
    HAVING avgRating > 0
    ORDER BY avgRating DESC
    LIMIT 6
";
$topresult = $conn->query($topsql);

if ($topresult->num_rows > 0) {
  $topPlaces = [];
  while ($row = $topresult->fetch_assoc()) {
    $topPlaces[] = $row;
  }
} else {
  $topPlaces = null;
}

$typesql = "SELECT * FROM typeplace";
$typeresult = $conn->query($typesql);

if ($typeresult->num_rows > 0) {
  // หากมีข้อมูล
  $typeplaces = [];
  while ($row = $typeresult->fetch_assoc()) {
    $typeplaces[] = $row;
  }
} else {
  echo "No data found";
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
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <link rel="stylesheet" href="style.css" />

  <title>Slothji Travel</title>
</head>

<body>

  <?php include 'navbar.php' ?>

  <section class="locate-group bg-dark" data-aos="fade-in">
    <div class="container-fluid p-0 m-0">
      <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
          <?php
          $slidesql = "SELECT HomeImg FROM homeslide WHERE HomeStatus = 1 ORDER BY HomeSort ASC";
          $slideresult = $conn->query($slidesql);
          if ($slideresult->num_rows > 0):
            $slideactive = true;
            while ($sliderow = $slideresult->fetch_assoc()):
          ?>
              <div id="slide1" class="carousel-item <?= $slideactive ? 'active' : '' ?>">
                <img src="./adminbd/slide/uploadSlide/<?= htmlspecialchars($sliderow['HomeImg']); ?>" class="d-block w-100" alt="Slide Image" onerror="this.onerror=null; this.src='./image/slothp1.png';">
              </div>
            <?php
              $slideactive = false;
            endwhile;
          else: ?>
            <div id="slide1" class="carousel-item active">
              <img src="./image/slothp1.png" class="d-block w-100" alt="No Slides Available">
            </div>
          <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>


  <!-- Poppular Places -->
  <section class="locate-group">
    <div class="container-xxl px-2 mt-5 hotplace-cont">
      <div class="pop-title mb-3">
        <h1 data-aos="fade-up">Popular Places</h1>
      </div>
      <div class="row d-flex justify-content-center">
        <?php if ($topPlaces): ?>
          <?php foreach ($topPlaces as $place): ?>
            <div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up">
              <div class="pop-wraper">
                <div class="pop-cars-area">
                  <div class="card">
                    <img src="./adminbd/placepage/uploads/places/<?= $place['PlaceImg'] ?>" alt="Image of <?= $place['PlaceTitle'] ?>">
                    <div class="overlay">
                      <div class="place-rating">
                        <i class="fa fa-star" style="color: gold;"></i>
                        <span style="color: gold;"><?= round($place['avgRating'], 1) ?></span>
                      </div>
                      <h3><?= $place['PlaceTitle'] ?></h3>
                      <p><?= $place['PlaceSubTitle'] ?></p>
                      <a href="blog.php?PlaceID=<?= $place['PlaceID'] ?>">ไปเลย!!</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="text-center mt-5" data-aos="fade-up">
            <p class="fs-4">ขณะนี้ยังไม่มีสถานที่แนะนำ</p>
          </div>
        <?php endif; ?>
      </div>
      <div class="container" data-aos="fade-up">
        <div class="mt-3 mb-3 text-center">
          <a class="card-places-btn" href="places.php">show more!</a>
        </div>
      </div>
    </div>
  </section>

  <?php
  if ($typeplaces): ?>
    <section class="pick-type-place">
      <div class="container">
        <div class="bottom-banner mb-5">
          <p class="fs-2 text-center" data-aos="fade-up">
            เลือกหมวดหมู่ตามความสนใจของคุณ
          </p>
        </div>
        <div class="row mb-5">
          <?php
          foreach ($typeplaces as $place): ?>
            <div class="box-item col-12 col-sm-12 col-md-6 col-lg-3 mb-3" data-aos="fade-up">
              <div class="flip-box">
                <div class="flip-box-front text-center" style="background-image: url('./adminbd/typepage/uploadType/<?= $place['TypeImg'] ?>');">
                  <div class="inner colo-white">
                    <h3 class="flip-box-header"><?= $place['TypeTitle'] ?></h3>
                  </div>
                </div>
                <div class="flip-box-back text-center" style="background-image: url('./adminbd/typepage/uploadType/<?= $place['TypeImg'] ?>');">
                  <div class="inner colo-white">
                    <p><?= $place['TypeDetail'] ?></p>
                    <button class="flip-box-button"><a class="flip-btn-text" href="/newsloth/places.php?type=<?= $place['TypeID'] ?>">Let's Go!</a></button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script type="text/javascript"
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="typeplace.js"></script>
  <script>
    AOS.init({
      duration: 1000, // ระยะเวลาแอนิเมชันในมิลลิวินาที
      once: true // เล่นเอฟเฟกต์ครั้งเดียว
    });
  </script>
</body>

</html>