<?php
session_start();

include '../db.php';

if (!isset($_SESSION['AdminUserName'])) {
    header("Location: ../adminlogin/adminlogin.php");
    exit();
}

$sql = "SELECT * FROM places ORDER BY PlaceNumbers ASC, PlaceName ASC";
$stmt = $conn->query($sql);

if (!$stmt) {
    die("Error: " . $conn->error);
}
$places = $stmt->fetch_all(MYSQLI_ASSOC);
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
    <title>Place</title>
</head>

<body id="body-pd">
    <?php include 'sidebar.php' ?>
    <div class="main-content">
        <div class="container place-table mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body mt-3">
                        <div class="table-responsive typetable">
                            <table id="places" name="places" class="display table caption-top" style="width: 100%;">
                                <caption>List of place type</caption>
                                <button class="btn btn-success btn-sm edit-btn" onclick="window.location.href='addplacepage.php'">
                                    <i class="fa-solid fa-plus"></i> <span class="add-text">เพิ่ม</span>
                                </button>
                                <button class="btn btn-danger btn-sm ms-3 me-3" id="bulkDeleteToggle">
                                    <i class="fa-solid fa-trash"></i> ลบหลายรายการ
                                </button>
                                <input type="checkbox" id="selectAll">เลือกทั้งหมด
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" width="5%"></th>
                                        <th class="text-center" width="5%">sequence of Place</th>
                                        <th class="text-center">PlaceName</th>
                                        <th class="text-center" width="15%">PlaceImg</th>
                                        <th class="text-center">PlaceSubTitle</th>
                                        <th class="text-center">PlaceLocation</th>
                                        <th class="text-center">PlaceStatus</th>
                                        <th class="text-center" width="10%">PlaceNumber</th>
                                        <th class="text-center" width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $num = 1;
                                    foreach ($places as $place) {
                                    ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="delete-checkbox" value="<?php echo $place['PlaceID']; ?>"><br>
                                            </td>

                                            <td class="text-center">
                                                <?php echo $num; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($place['PlaceName']); ?></td>
                                            <td class="text-center">
                                                <img id="preview" src="uploads/places/<?php echo $place['PlaceImg']; ?>" alt="Image Preview" class="img-fluid" style="width: 150px; height: 150px;">
                                            </td>
                                            <td><?php echo htmlspecialchars($place['PlaceSubTitle']); ?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center mt-3">
                                                    <button type="button"
                                                        class="toggle-map-modal btn btn-primary"
                                                        data-url="<?php echo htmlspecialchars($place['PlaceLocation']); ?>"
                                                        style="display: flex; align-items: center;">
                                                        ดูแผนที่
                                                    </button>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center mt-3">

                                                    <?php if ($place['isVisible']): ?>
                                                        <button class="btn btn-success btn-sm toggle-visibility" data-id="<?php echo $place['PlaceID']; ?>" data-status="1">
                                                            <i class="fa-solid fa-eye" style="color: #fff;"></i> แสดง
                                                        </button>

                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-sm toggle-visibility" data-id="<?php echo $place['PlaceID']; ?>" data-status="0">
                                                            <i class="fa-solid fa-eye-slash" style="color: #fff;"></i> ซ่อน
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <td>
                                                <input type="number" class="form-control sort-input mt-3"
                                                    style="text-align: center; vertical-align: middle; height: 100%;"
                                                    data-id="<?php echo $place['PlaceID']; ?>"
                                                    value="<?php echo $place['PlaceNumbers']; ?>"
                                                    min="1" onchange="validateSortInput(this)">
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center mt-3">
                                                    <a href="editplacepage.php?PlaceID=<?php echo $place['PlaceID']; ?>" class="btn btn-warning btn-sm edit-btn ">
                                                        <i class="fa-solid fa-pen" style="color: #fff;"></i>
                                                    </a>

                                                    <button class="btn btn-danger btn-sm delete-btn ms-2" onclick="confirmDelete(<?php echo $place['PlaceID']; ?>)">
                                                        <i class="fa-solid fa-trash" style="color: #fff;"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                        $num++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <button id="sortPlacesBtn" class="btn btn-primary mb-3" style="float: right; margin-right: 100px;">จัดเรียง</button>

                        </div>
                        <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="mapModalLabel">แผนที่</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe id="mapFrame" src="" width="100%" height="400" style="border:0;" allowfullscreen=""></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src=dashboardscript.js></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
        $(document).ready(function() {
            $(document).on("click", ".toggle-map-modal", function() {
                var mapUrl = $(this).data("url").trim();

                if (!mapUrl || !mapUrl.startsWith("https://www.google.com/maps/embed")) {
                    alert("ลิงก์แผนที่ไม่ถูกต้อง!");
                    return;
                }

                $("#mapFrame").attr("src", mapUrl);
                $("#mapModal").modal("show");
            });

            $("#mapModal").on("hidden.bs.modal", function() {
                $("#mapFrame").attr("src", "");
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#places').DataTable({
                "order": [
                    [1, 'asc']
                ],
                "stateSave": true,
            });

            $('#selectAll').on('click', function() {
                var rows = table.rows({
                    search: 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            $('#places tbody').on('change', '.delete-checkbox', function() {
                $('#selectAll').prop('checked', $('.delete-checkbox:checked').length === $('.delete-checkbox').length);
            });

            $('#bulkDeleteToggle').on('click', function() {
                var selectedIDs = $('.delete-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIDs.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเลือกข้อมูลที่ต้องการลบ',
                        text: 'คุณต้องเลือกอย่างน้อย 1 รายการ!',
                        confirmButtonText: 'ตกลง'
                    });
                    return;
                }

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: `คุณต้องการลบ ${selectedIDs.length} รายการที่เลือก!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_multiple_places.php',
                            type: 'POST',
                            data: {
                                placeIDs: selectedIDs
                            },
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ลบสำเร็จ!',
                                        text: response.message,
                                        confirmButtonText: 'ตกลง'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: response.message,
                                        confirmButtonText: 'ตกลง'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error: " + error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ลบไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาด: ' + error,
                                    confirmButtonText: 'ตกลง'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        function confirmDelete(placeID) {
            console.log("PlaceID: " + placeID);

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "ข้อมูลจะถูกลบและไม่สามารถกู้คืนได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบข้อมูล!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = `deleteplace.php?PlaceID=${placeID}`;

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);

                            if (data.success) {
                                Swal.fire({
                                    title: 'ลบข้อมูลสำเร็จ!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'ตกลง'
                                }).then(() => {
                                    window.location.href = 'adminplace.php';
                                });
                            } else {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'ตกลง'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                            Swal.fire({
                                title: 'ข้อผิดพลาด!',
                                text: "ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์",
                                icon: 'error',
                                confirmButtonText: 'ตกลง'
                            });
                        }
                    });
                }
            });
        }
    </script>

    <script>
        $("#sortPlacesBtn").click(function() {
            let sortData = [];
            $(".sort-input").each(function() {
                let placeID = $(this).data("id");
                let placeNumer = $(this).val();
                sortData.push({
                    PlaceID: placeID,
                    PlaceNumbers: placeNumer
                });
            });

            $.ajax({
                url: "update_place_number.php",
                type: "POST",
                data: {
                    sortData: JSON.stringify(sortData)
                },
                success: function() {
                    Swal.fire("สำเร็จ!", "จัดเรียงสถานที่ สำเร็จ", "success").then(() => {
                        location.reload();
                    });
                },
            });
        });
    </script>

    <script>
        $(document).on("click", ".toggle-visibility", function() {
            const button = $(this);
            const placeID = button.data("id");
            const newStatus = button.data("status") === 1 ? 0 : 1;

            fetch("toggle_visibility.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        PlaceID: placeID,
                        isVisible: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Server Response:", data);
                    if (data.success) {
                        button.toggleClass("btn-success btn-secondary");
                        button.data("status", newStatus);
                        button.html(newStatus === 1 ? '<i class="fa-solid fa-eye"></i> แสดง' : '<i class="fa-solid fa-eye-slash"></i> ซ่อน');

                        Swal.fire({
                            title: "เปลี่ยนสถานะสำเร็จ!",
                            text: newStatus === 1 ? "สถานะถูกเปลี่ยนเป็น 'แสดง'" : "สถานะถูกเปลี่ยนเป็น 'ซ่อน'",
                            icon: "success",
                            confirmButtonText: "ตกลง"
                        });
                    } else {
                        Swal.fire({
                            title: "เกิดข้อผิดพลาด!",
                            text: "ไม่สามารถเปลี่ยนสถานะได้: " + data.message,
                            icon: "error",
                            confirmButtonText: "ตกลง"
                        });
                    }
                })
                .catch(error => {
                    console.error("Fetch Error:", error);
                    Swal.fire({
                        title: "ข้อผิดพลาด!",
                        text: "ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์",
                        icon: "error",
                        confirmButtonText: "ตกลง"
                    });
                });
        });
    </script>
    <script>
        function validateSortInput(input) {
            let originalValue = input.defaultValue;
            let value = input.value;

            if (isNaN(value) || value <= 0) {
                Swal.fire({
                    icon: "error",
                    title: "กรุณาใส่เฉพาะค่าที่เป็นเลข (ไม่รับค่าติดลบและ 0)",
                    showConfirmButton: true,
                });
                input.value = originalValue;
            }
        }
    </script>
</body>

</html>