<?php
session_start();
include '../db.php';
if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php");
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
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="sweetalert2.min.css">

    <title>Home Slide</title>
</head>

<body id="body-pd">
    <?php include 'sidebar.php' ?>

    <div class="height-100 bg-light">
        <div class="container type-table pt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <div class="table-responsive typetable">
                            <table id="homeslide" name="homeslide" class="display table caption-top" style="width: 100%;">
                                <caption>List of Slide</caption>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="fa-solid fa-plus"></i> <span class="add-text">เพิ่ม</span>
                                </button>
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" width="15%">Home Number</th>
                                        <th class="text-center">HomeImg</th>
                                        <th class="text-center" width="10%">HomeStatus</th>
                                        <th class="text-center" width="5%">HomeSort</th>
                                        <th class="text-center" width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $conn->query("SELECT * FROM homeslide ORDER BY HomeSort ASC");
                                    $num = 1;
                                    if (!$stmt) {
                                        die("Error: " . $conn->error);
                                    }
                                    $homeslide = $stmt->fetch_all(MYSQLI_ASSOC);
                                    foreach ($homeslide as $slide) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $num++; ?></td>
                                            <td class="text-center">
                                                <img src="uploadSlide/<?php echo htmlspecialchars($slide['HomeImg']); ?>" alt="Home Image" style="width: 150px; height: auto;">
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center mt-3">

                                                    <?php if ($slide['HomeStatus']): ?>
                                                        <button class="btn btn-success btn-sm slide-toggle-visibility" data-id="<?php echo $slide['HomeID']; ?>" data-status="1">
                                                            <i class="fa-solid fa-eye" style="color: #fff;"></i> แสดง
                                                        </button>

                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-sm slide-toggle-visibility" data-id="<?php echo $slide['HomeID']; ?>" data-status="0">
                                                            <i class="fa-solid fa-eye-slash" style="color: #fff;"></i> ซ่อน
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control sort-input"
                                                    style="text-align: center; vertical-align: middle; height: 100%;"
                                                    data-id="<?php echo $slide['HomeID']; ?>"
                                                    value="<?php echo max(1, $slide['HomeSort']); ?>" min="1"
                                                    onchange="validateSortInput(this)">
                                            </td>


                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $slide['HomeID']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">
                                                    <i class="fa-solid fa-pen" style="color: #fff;"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $slide['HomeID']; ?>">
                                                    <i class="fa-solid fa-trash" style="color: #fff;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                            </table>
                            <button id="sortSlidesBtn" class="btn btn-primary" style="float: right; margin-right: 100px;">จัดเรียง</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade modal-dialog-scrollable" id="addModal" tabindex="-2" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มสไลด์ใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addSlideForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addHomeImg" class="form-label">เลือกรูปภาพ</label>
                            <input type="file" class="form-control" id="addHomeImg" name="HomeImg" required accept="image/jpeg, image/png" onchange="validateImage('addHomeImg')">
                            <img id="addPreviewImg" src="#" class="img-fluid mt-2" style="display:none; max-height:150px;">
                        </div>
                        <div class="mb-3">
                            <label for="addHomeSort" class="form-label">ลำดับการแสดง (เว้นว่างเพื่อให้รูปอยู่ลำดับสุดท้าย)</label>
                            <input type="number" class="form-control" id="addHomeSort" name="HomeSort">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">เพิ่ม</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- modal edit -->
    <div class="modal fade modal-dialog-scrollable" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขรูป Slide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="editForm" enctype="multipart/form-data">
                            <input type="hidden" id="editHomeID" name="HomeID">

                            <div class="mb-3">
                                <label for="editHomeImg" class="form-label">อัปโหลดรูปภาพ Slide</label>
                                <input type="file" class="form-control" id="editHomeImg" name="HomeImg" accept="image/jpeg, image/png" onchange="validateImage('editHomeImg')">
                                <img id="editPreviewImg" src="#" alt="Preview Image" style="width: 300px; height: 300px; display: none; margin-top: 10px;">
                                <br>
                                <label>รูปภาพเก่า:</label>
                                <img id="oldPreviewImg" src="" alt="Old Image" style="width: 300px; height: 300px; display: none; margin-top: 10px;">
                            </div>

                            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                        </form>
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
    <script type="text/javascript" src="slide.js"></script>

</body>

</html>