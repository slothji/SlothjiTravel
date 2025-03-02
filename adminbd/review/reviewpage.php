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
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="sweetalert2.min.css">
    <title>Review Page</title>
</head>

<body id="body-pd">
    <?php include 'sidebar.php' ?>

    <div class="height-100 bg-light">
        <div class="container type-table pt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <div class="table-responsive typetable">
                            <table id="review" name="review" class="display table caption-top" style="width: 100%;">
                                <caption>List of place type</caption>
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" width="10%">ReviewID</th>
                                        <th class="text-center">UserName</th>
                                        <th class="text-center">PlaceName</th>
                                        <th class="text-center">Rating</th>
                                        <th class="text-center">Comment</th>
                                        <th class="text-center">ReviewDate</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $conn->query("
                                    SELECT 
                                        reviews.*, 
                                        users.UserName, 
                                        places.PlaceName
                                    FROM 
                                        reviews
                                    JOIN 
                                        users ON reviews.UserID = users.UserID
                                    JOIN 
                                        places ON reviews.PlaceID = places.PlaceID
                                    ");
                                    if (!$stmt) {
                                        die("Error: " . $conn->error);
                                    }

                                    // ดึงข้อมูลทั้งหมดในรูปแบบ associative array
                                    $review = $stmt->fetch_all(MYSQLI_ASSOC);

                                    foreach ($review as $view) {
                                        // ใช้ $view['UserName'] และ $view['PlaceName'] ได้
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlspecialchars($view['ReviewID']); ?></td>
                                            <td><?php echo htmlspecialchars($view['UserName']); ?></td>
                                            <td><?php echo htmlspecialchars($view['PlaceName']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($view['Rating']); ?></td>
                                            <td><?php echo htmlspecialchars($view['Comment']); ?></td>
                                            <td><?php echo htmlspecialchars($view['ReviewDate']); ?></td>
                                            <td class="text-center">
                                                <!-- <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $view['ReviewID']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">
                                                    <i class="fa-solid fa-pen" style="color: #fff;"></i>
                                                </button> -->
                                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $view['ReviewID']; ?>">
                                                    <i class="fa-solid fa-trash" style="color: #fff;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
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
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editTypeID" name="TypeID">
                        <div class="mb-3">
                            <label for="editTypeTitle" class="form-label">Star</label>
                            <input type="text" class="form-control" id="editTypeTitle" name="TypeTitle">
                        </div>

                        <div class="mb-3">
                            <label for="editTypeDetail" class="form-label">Your Comment</label>
                            <textarea class="form-control" id="editTypeDetail" name="TypeDetail"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container detail-content">
        <!-- Modal สำหรับเพิ่มข้อมูล -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addModalLabel">เพิ่มประเภทสถานที่</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm">
                            <div class="mb-3">
                                <label for="addTypeTitle" class="form-label">ชื่อประเภท</label>
                                <input type="text" class="form-control" id="addTypeTitle" required>
                            </div>
                            <div class="mb-3">
                                <label for="addTypeImg" class="form-label">รูปภาพ (image.jpg)</label>
                                <input type="text" class="form-control" id="addTypeImg" required>
                            </div>
                            <div class="mb-3">
                                <label for="addTypeDetail" class="form-label">รายละเอียด</label>
                                <textarea class="form-control" id="addTypeDetail" rows="3" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary" id="submitAdd">เพิ่มข้อมูล</button>
                        </form>
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
        let table = new DataTable('#review');
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
        document.addEventListener('DOMContentLoaded', function() {
            // Edit button functionality
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const typeID = this.getAttribute('data-id');
                    // Fetch the data for this TypeID (e.g., via AJAX) and fill the form
                    fetch(`getType.php?id=${typeID}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('editTypeID').value = data.TypeID;
                            document.getElementById('editTypeTitle').value = data.TypeTitle;
                            document.getElementById('editTypeImg').value = data.TypeImg;
                            document.getElementById('editTypeDetail').value = data.TypeDetail;
                        });

                });
            });

            // Delete button functionality
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this item?')) {
                        const formData = new FormData();
                        formData.append('TypeID', this.getAttribute('data-id'));

                        fetch('deleteType.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(data => {
                                if (data.trim() === 'success') {
                                    alert('Data deleted successfully!');
                                    location.reload();
                                } else {
                                    alert('Error deleting data: ' + data);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An unexpected error occurred.');
                            });
                    }
                });
            });

            // Submit edit form
            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                console.log(...formData); // ตรวจสอบข้อมูลที่ถูกส่งไป

                fetch('editType.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data); // ดูข้อความที่ส่งกลับจากเซิร์ฟเวอร์
                        if (data === 'success') {
                            alert('Item updated successfully!');
                            location.reload();
                        } else {
                            alert('Item updated successfully!');
                            location.reload(); // แสดงข้อความ Error จากเซิร์ฟเวอร์
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error); // แสดง Error ใน Console
                        alert('An unexpected error occurred.');
                    });
            });

            // add items
            document.getElementById('submitAdd').addEventListener('click', function() {
                const formData = new FormData();
                formData.append('TypeTitle', document.getElementById('addTypeTitle').value);
                formData.append('TypeImg', document.getElementById('addTypeImg').value);
                formData.append('TypeDetail', document.getElementById('addTypeDetail').value);

                fetch('addType.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            alert('Data added successfully!');
                            location.reload();
                        } else {
                            alert('Error adding data: ' + data);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                    });
            });

        });
    </script>

</body>

</html>