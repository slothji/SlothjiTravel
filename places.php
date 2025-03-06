<?php
session_start();
include 'dataDB.php';


if (!isset($_SESSION['redirect_url'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}

$resultType = [];

$sqlType = "SELECT * FROM typeplace";
$typeQuery = $conn->query($sqlType);
if ($typeQuery->num_rows > 0) {
    while ($row = $typeQuery->fetch_assoc()) {
        $resultType[] = $row;
    }
}

$itemsPerPage = 9;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max($currentPage, 1);
$offset = ($currentPage - 1) * $itemsPerPage;

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "
        SELECT places.*, typeplace.TypeTitle 
        FROM places 
        JOIN typeplace ON places.TypeID = typeplace.TypeID
        WHERE places.PlaceTitle LIKE '%$search%' AND places.IsVisible = 1
        ORDER BY places.PlaceNumbers ASC  
        LIMIT $itemsPerPage OFFSET $offset
    ";
    $countSql = "
        SELECT COUNT(*) AS total 
        FROM places 
        WHERE PlaceTitle LIKE '%$search%' AND IsVisible = 1
    ";
} elseif (isset($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $sql = "
        SELECT places.*, typeplace.TypeTitle 
        FROM places 
        JOIN typeplace ON places.TypeID = typeplace.TypeID
        WHERE places.TypeID = '$type' AND places.IsVisible = 1
        ORDER BY places.PlaceNumbers ASC  
        LIMIT $itemsPerPage OFFSET $offset
    ";
    $countSql = "
        SELECT COUNT(*) AS total 
        FROM places 
        WHERE TypeID = '$type' AND IsVisible = 1
    ";
} else {
    $sql = "
        SELECT places.*, typeplace.TypeTitle 
        FROM places 
        JOIN typeplace ON places.TypeID = typeplace.TypeID
        WHERE places.IsVisible = 1
        ORDER BY places.PlaceNumbers ASC  
        LIMIT $itemsPerPage OFFSET $offset
    ";
    $countSql = "SELECT COUNT(*) AS total FROM places WHERE IsVisible = 1";
}

$result = $conn->query($sql);

$countResult = $conn->query($countSql);
$totalItems = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$myplaces = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $myplaces[] = $row;
    }
}

$placeReviews = [];
foreach ($myplaces as $place) {
    $placeID = $place['PlaceID'];
    $sqlReview = "SELECT AVG(Rating) AS avgRating, COUNT(*) AS reviewCount FROM reviews WHERE PlaceID = '$placeID'";
    $reviewResult = $conn->query($sqlReview);
    if ($reviewResult && $reviewRow = $reviewResult->fetch_assoc()) {
        if ($reviewRow['reviewCount'] > 0) {
            $placeReviews[$placeID] = round($reviewRow['avgRating'], 1);
        } else {
            $placeReviews[$placeID] = 0;
        }
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
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="style.css" />

    <title>PLACES</title>
</head>

<body>
    <!-- navbar -->
    <?php include 'navbar.php'; ?>

    <!-- banner -->
    <section class="locate-group">
        <div>
            <div class="container-fluid p-0 m-0">
                <div class="places-group">
                    <img src="./image/templeEx.jpg" class="placeBG d-block w-100">
                    <div class="place-title">
                        <h1>Places</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($resultType): ?>
        <section class="locate-group">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-3 col-12 mb-4" data-aos="fade-up">
                        <p>รายการสถานที่</p>
                        <form class="example mb-2" autocomplete="off" action="places.php" method="GET" style="margin:auto;max-width:300px">
                            <input type="text" placeholder="ค้นหาสถานที่..." name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                            <button type="submit" class="search-btn"><i class="fa fa-search search-btn"></i></button>
                        </form>
                        <?php foreach ($resultType as $place): ?>
                            <div class="list-group">
                                <a href="/newsloth/places.php?type=<?= $place['TypeID'] ?>"
                                    class="list-group-item list-group-item-action type-btn 
                                    <?php if (isset($_GET['type'])) {
                                        if ($_GET['type'] == $place['TypeID']) {
                                            echo 'type-btn-active';
                                        }
                                    } ?>">
                                    <?= $place['TypeTitle'] ?>
                                </a>

                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="col-12 col-lg-9 " data-aos="fade-up">
                        <div class="row">
                            <?php if (!empty($myplaces)): ?>
                                <?php foreach ($myplaces as $place): ?>
                                    <div class="show-card col-sm-12 col-md-6 col-lg-4 mb-5">
                                        <div class="card place-card-body" data-tilt>
                                            <img src="./adminbd/placepage/uploads/places/<?= $place['PlaceImg'] ?>" alt="...">
                                            <div class="card-body place-card-text">

                                                <!-- คะแนนรีวิวสถานที่ -->
                                                <?php if (isset($placeReviews[$place['PlaceID']])): ?>

                                                    <div class="place-rating">
                                                        <i class="fa fa-star" style="color: gold;"></i>
                                                        <span><?= $placeReviews[$place['PlaceID']] ?></span>
                                                    </div>

                                                <?php endif; ?>
                                                <h5 class="card-title mb-3"><?= $place['PlaceTitle'] ?></h5>
                                                <p class="card-text place-sub"><?= $place['PlaceSubTitle'] ?></p>
                                                <a href="blog.php?PlaceID=<?= $place['PlaceID']; ?>" class="btn btn-primary blog-btn">ดูรายละเอียด</a>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center">ไม่พบข้อมูลที่ค้นหา</p>
                            <?php endif; ?>

                            <?php if (!empty($myplaces)): ?>
                                <div class="paging container" data-aos="fade-up">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination pagination-dark">
                                            <!-- ปุ่มก่อนหน้า -->
                                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                                <a class="page-link pev-btn" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>

                                            <!-- ลิงก์หน้าทั้งหมด -->
                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                                    <a class="page-link page-num" href="?page=<?= $i ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <!-- ปุ่มถัดไป -->
                                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                                <a class="page-link next-btn" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- footer -->
    <?php include 'footer.php' ?>

    <script type="text/javascript" src="vanilla-tilt.js"></script>
    <script type="text/javascript">
        VanillaTilt.init(document.querySelectorAll("place-card-body"), {
            max: 25,
            speed: 400
        });
    </script>

    </section>
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
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>

</body>

</html>