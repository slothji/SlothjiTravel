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
    <title>Type Places</title>
</head>

<body id="body-pd">
    <?php include 'sidebar.php' ?>

    <div class="height-100 bg-light">
        <div class="container type-table pt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <div class="table-responsive typetable">
                            <table id="typeplace" name="typeplace" class="display table caption-top" style="width: 100%;">
                                <caption>List of place type</caption>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="fa-solid fa-plus"></i> <span class="add-text">เพิ่ม</span>
                                </button>
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" width="10%">TypeNumber</th>
                                        <th class="text-center">TypeTitle</th>
                                        <th class="text-center">TypeImg</th>
                                        <th class="text-center">TypeDetail</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $conn->query("SELECT * FROM typeplace");
                                    $num = 1;
                                    if (!$stmt) {
                                        die("Error: " . $conn->error);
                                    }
                                    // You don't need to call execute() here
                                    $typeplace = $stmt->fetch_all(MYSQLI_ASSOC); // Use fetch_all for an array of results
                                    foreach ($typeplace as $type) {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $num++; ?></td>
                                            <td><?php echo htmlspecialchars($type['TypeTitle']); ?></td>
                                            <td class="text-center">
                                                <img src="uploadType/<?php echo htmlspecialchars($type['TypeImg']); ?>" alt="Type Image" style="width: 60px; height: 80px;">
                                            </td>

                                            <td><?php echo htmlspecialchars($type['TypeDetail']); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $type['TypeID']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">
                                                    <i class="fa-solid fa-pen" style="color: #fff;"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $type['TypeID']; ?>">
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
    <div class="modal fade modal-dialog-scrollable" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="editForm" enctype="multipart/form-data">
                            <input type="hidden" id="editTypeID" name="TypeID">
                            <div class="mb-3">
                                <label for="editTypeTitle" class="form-label">ชื่อประเภท</label>
                                <input type="text" class="form-control" id="editTypeTitle" name="TypeTitle">
                            </div>
                            <div class="mb-3">
                                <label for="editTypeImg" class="form-label">อัปโหลดรูปภาพ (ขนาด 306 x 475 px)</label>
                                <input type="file" class="form-control" id="editTypeImg" name="TypeImg" accept="image/*" onchange="validateImage()">
                                <img id="editPreviewImg" src="#" alt="Preview Image" style="width: 300px; height: 300px; display: none; margin-top: 10px;">
                                <br>
                                <label>รูปภาพเก่า:</label>
                                <img id="oldPreviewImg" src="" alt="Old Image" style="width: 300px; height: 300px; display: none; margin-top: 10px;">
                            </div>
                            <div class="mb-3">
                                <label for="editTypeDetail" class="form-label">รายละเอียด</label>
                                <textarea class="form-control" id="editTypeDetail" name="TypeDetail"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="container detail-content">
        <!-- Modal สำหรับเพิ่มข้อมูล -->
        <div class="modal fade modal-dialog-scrollable" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addModalLabel">เพิ่มประเภทสถานที่</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="addTypeTitle" class="form-label">ชื่อประเภท</label>
                                <input type="text" class="form-control" id="addTypeTitle" required>
                            </div>
                            <div class="mb-3">
                                <label for="addTypeImg" class="form-label">อัปโหลดรูปภาพ (ขนาด 306 x 475 px)</label>
                                <input type="file" class="form-control" id="addTypeImg" name="TypeImg" accept="image/*" required onchange="validateImage()">
                                <img id="addPreviewImg" src="#" alt="Preview Image" style="max-width: 100%; display: none; margin-top: 10px;">
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
                        window.location.href = "../adminlogin/adminlogin.php";
                    });
                }, 30 * 60 * 1000); // 30 นาที
            }

            // ตรวจจับทุก Event ที่เกี่ยวกับการใช้งานของผู้ใช้
            ["mousemove", "keypress", "click", "scroll", "touchstart"].forEach(event => {
                document.addEventListener(event, resetTimer);
            });

            resetTimer();
        });
    </script>

    <script>
        function validateImage(inputId, previewId) {
            const fileInput = document.getElementById(inputId);
            const file = fileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/png'];
            const maxFileSize = 3 * 1024 * 1024; // 3 MB
            const targetWidth = 310;
            const targetHeight = 475;

            if (!file) return;

            // ตรวจสอบชนิดไฟล์
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'ชนิดไฟล์ไม่ถูกต้อง',
                    text: 'โปรดเลือกไฟล์ .jpg หรือ .png เท่านั้น',
                });
                fileInput.value = ''; // ล้างค่า input
                return;
            }

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;

                img.onload = function() {
                    let canvas = document.createElement('canvas');
                    let ctx = canvas.getContext('2d');

                    let newWidth = img.width;
                    let newHeight = img.height;
                    let needResize = false;

                    // ตรวจสอบขนาดภาพ
                    if (newWidth !== targetWidth || newHeight !== targetHeight) {
                        newWidth = targetWidth;
                        newHeight = targetHeight;
                        needResize = true;
                    }

                    // ตรวจสอบขนาดไฟล์
                    if (file.size > maxFileSize) {
                        needResize = true;
                    }

                    if (needResize) {
                        canvas.width = newWidth;
                        canvas.height = newHeight;
                        ctx.drawImage(img, 0, 0, newWidth, newHeight);

                        canvas.toBlob(blob => {
                            const resizedFile = new File([blob], file.name, {
                                type: file.type
                            });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(resizedFile);
                            fileInput.files = dataTransfer.files;

                            // แสดงตัวอย่างรูป
                            document.getElementById(previewId).src = URL.createObjectURL(blob);
                            document.getElementById(previewId).style.display = "block";
                        }, file.type);
                    } else {
                        // แสดงตัวอย่างรูปถ้าไม่ต้องปรับขนาด
                        document.getElementById(previewId).src = e.target.result;
                        document.getElementById(previewId).style.display = "block";
                    }
                };
            };
        }

        document.getElementById("addTypeImg").addEventListener("change", function() {
            validateImage("addTypeImg", "addPreviewImg");
        });

        document.getElementById("editTypeImg").addEventListener("change", function() {
            validateImage("editTypeImg", "editPreviewImg");
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId);

                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener("click", () => {
                        nav.classList.toggle("show-slidbar");
                        toggle.classList.toggle("bx-x");
                        bodypd.classList.toggle("body-pd");
                        headerpd.classList.toggle("body-pd");
                    });
                }
            };

            showNavbar("header-toggle", "nav-bar", "body-pd", "header");

            document.querySelectorAll(".nav_link").forEach(link => {
                link.addEventListener("click", function() {
                    document.querySelectorAll(".nav_link").forEach(l => l.classList.remove("active"));
                    this.classList.add("active");
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ปุ่มแก้ไขข้อมูล
            document.querySelectorAll(".edit-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const typeID = this.getAttribute("data-id");

                    fetch(`getType.php?id=${typeID}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById("editTypeID").value = data.TypeID;
                            document.getElementById("editTypeTitle").value = data.TypeTitle;
                            document.getElementById("editTypeDetail").value = data.TypeDetail;

                            if (data.TypeImg) {
                                document.getElementById("oldPreviewImg").src = `uploadType/${data.TypeImg}`;
                                document.getElementById("oldPreviewImg").style.display = "block";
                            } else {
                                document.getElementById("oldPreviewImg").style.display = "none";
                            }
                        })
                        .catch(error => console.error("Error fetching type data:", error));
                });
            });

            // ปุ่มลบข้อมูล
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    Swal.fire({
                        title: "คุณแน่ใจหรือไม่?",
                        text: "คุณต้องการลบข้อมูลนี้จริงหรือ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "ใช่, ลบเลย!",
                        cancelButtonText: "ยกเลิก",
                    }).then(result => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append("TypeID", this.getAttribute("data-id"));

                            fetch("deleteType.php", {
                                    method: "POST",
                                    body: formData
                                })
                                .then(response => response.text())
                                .then(data => {
                                    if (data.trim() === "success") {
                                        Swal.fire("ลบข้อมูลสำเร็จ!", "", "success").then(() => location.reload());
                                    } else {
                                        Swal.fire("เกิดข้อผิดพลาด!", data, "error");
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                    Swal.fire("เกิดข้อผิดพลาด!", "กรุณาลองใหม่", "error");
                                });
                        }
                    });
                });
            });

            // ฟอร์มแก้ไขข้อมูล
            document.getElementById("editForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("editType.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === "success") {
                            // ปิด Modal ก่อน
                            $("#editModal").modal("hide");

                            // รอให้ Modal ปิดก่อนแล้วค่อยแสดง SweetAlert
                            setTimeout(() => {
                                Swal.fire("แก้ไขสำเร็จ!", "", "success").then(() => location.reload());
                            }, 300);
                        } else {
                            Swal.fire("เกิดข้อผิดพลาด!", data, "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire("เกิดข้อผิดพลาด!", "กรุณาลองใหม่", "error");
                    });
            });


            // ปุ่มเพิ่มข้อมูล
            document.getElementById("submitAdd").addEventListener("click", function() {
                const formData = new FormData();
                formData.append("TypeTitle", document.getElementById("addTypeTitle").value);
                formData.append("TypeImg", document.getElementById("addTypeImg").files[0]);
                formData.append("TypeDetail", document.getElementById("addTypeDetail").value);

                fetch("addType.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === "success") {
                            // ปิด Modal ก่อน
                            $("#addModal").modal("hide");

                            // รอให้ Modal ปิดก่อนแล้วค่อยแสดง SweetAlert
                            setTimeout(() => {
                                Swal.fire({
                                    icon: "success",
                                    title: "เพิ่มข้อมูลสำเร็จ!",
                                    confirmButtonText: 'ตกลง',
                                }).then(() => location.reload());
                            }, 300);
                        } else {
                            Swal.fire("เกิดข้อผิดพลาด!", data, "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire("เกิดข้อผิดพลาด!", "กรุณาลองใหม่", "error");
                    });
            });

        });
    </script>

    <script>
        document.getElementById("addTypeImg").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById("addPreviewImg").src = e.target.result;
                    document.getElementById("addPreviewImg").style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById("editTypeImg").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById("editPreviewImg").src = e.target.result;
                    document.getElementById("editPreviewImg").style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>

</html>