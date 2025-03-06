let table = new DataTable("#homeslide");

document.addEventListener("DOMContentLoaded", function () {
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

document.addEventListener("DOMContentLoaded", function (event) {
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

  const linkColor = document.querySelectorAll(".nav_link");

  function colorLink() {
    if (linkColor) {
      linkColor.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");
    }
  }
  linkColor.forEach((l) => l.addEventListener("click", colorLink));
});

function validateImage(inputId) {
  let fileInput = document.getElementById(inputId);
  let file = fileInput.files[0];

  if (!file) return;

  let allowedTypes = ["image/jpeg", "image/png"];
  if (!allowedTypes.includes(file.type)) {
    let modalId = inputId === "addHomeImg" ? "addModal" : "editModal";
    let modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    modal.hide();

    Swal.fire(
      "ไฟล์ไม่รองรับ",
      "กรุณาเลือกไฟล์ .jpg หรือ .png เท่านั้น",
      "error"
    );
    fileInput.value = "";
    return;
  }

  let maxSize = 5 * 1024 * 1024; // 5MB
  let img = new Image();
  img.src = URL.createObjectURL(file);
  img.onload = function () {
    let width = img.naturalWidth;
    let height = img.naturalHeight;

    if (file.size > maxSize || width !== 1520 || height !== 725) {
      resizeImage(img, 1520, 725, file.type, inputId, fileInput);
    } else {
      previewImage(file, inputId);
    }
  };
}

function resizeImage(
  img,
  targetWidth,
  targetHeight,
  fileType,
  inputId,
  fileInput
) {
  let canvas = document.createElement("canvas");
  let ctx = canvas.getContext("2d");

  canvas.width = targetWidth;
  canvas.height = targetHeight;

  ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

  let quality = fileType === "image/png" ? 0.92 : 0.8;
  canvas.toBlob(
    (blob) => {
      let resizedFile = new File([blob], "resized_image.jpg", {
        type: "image/jpeg",
      });

      let dataTransfer = new DataTransfer();
      dataTransfer.items.add(resizedFile);
      fileInput.files = dataTransfer.files;

      previewImage(resizedFile, inputId);
    },
    "image/jpeg",
    quality
  );
}

function previewImage(file, inputId) {
  let reader = new FileReader();
  reader.onload = function (e) {
    let previewId =
      inputId === "addHomeImg" ? "addPreviewImg" : "editPreviewImg";
    let previewImg = document.getElementById(previewId);
    previewImg.src = e.target.result;
    previewImg.style.display = "block";
  };
  reader.readAsDataURL(file);
}

// add items
$(document).ready(function () {
  $("#addHomeImg").change(function () {
    let reader = new FileReader();
    reader.onload = function (e) {
      $("#addPreviewImg").attr("src", e.target.result).show();
    };
    reader.readAsDataURL(this.files[0]);
  });

  $("#addSlideForm").submit(function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
      url: "addSlide.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        $("#addModal").modal("hide");
        setTimeout(() => {
          Swal.fire({
            title: "เพิ่มสไลด์สำเร็จ!",
            text: "รูปภาพถูกเพิ่มเรียบร้อย",
            icon: "success",
            confirmButtonText: "ตกลง",
          }).then(() => {
            location.reload();
          });
        }, 500);
      },
      error: function () {
        Swal.fire("เกิดข้อผิดพลาด!", "กรุณาลองใหม่อีกครั้ง", "error");
      },
    });
  });
});

// edit item
$(document).ready(function () {
  $(".edit-btn").click(function () {
    let homeID = $(this).data("id");

    $.ajax({
      url: "getSlide.php",
      type: "POST",
      data: { HomeID: homeID },
      dataType: "json",
      success: function (data) {
        $("#editHomeID").val(data.HomeID);
        $("#oldPreviewImg")
          .attr("src", "uploadSlide/" + data.HomeImg)
          .show();
        $("#editPreviewImg").hide();
      },
    });
  });

  $("#editHomeImg").change(function () {
    let reader = new FileReader();
    reader.onload = function (e) {
      $("#editPreviewImg").attr("src", e.target.result).show();
    };
    reader.readAsDataURL(this.files[0]);
  });

  $("#editForm").submit(function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
      url: "editSlide.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        $("#editModal").modal("hide");
        setTimeout(() => {
          Swal.fire({
            title: "แก้ไขสำเร็จ!",
            text: "รูปภาพถูกอัปเดตเรียบร้อย",
            icon: "success",
            confirmButtonText: "ตกลง",
          }).then(() => {
            location.reload();
          });
        }, 500);
      },
      error: function () {
        Swal.fire("เกิดข้อผิดพลาด!", "กรุณาลองใหม่อีกครั้ง", "error");
      },
    });
  });
});

$(document).on("click", ".delete-btn", function () {
  let homeID = $(this).data("id");

  $("#editSlideModal, #addSlideModal").modal("hide");
  setTimeout(() => {
    Swal.fire({
      title: "ต้องการลบรูปนี้หรือไม่?",
      text: "การกระทำนี้ไม่สามารถย้อนกลับได้",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "ลบ",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "deleteSlide.php",
          type: "POST",
          data: { HomeID: homeID },
          success: function () {
            Swal.fire("ลบสำเร็จ!", "รูปภาพถูกลบเรียบร้อยแล้ว", "success").then(
              () => {
                location.reload();
              }
            );
          },
        });
      }
    });
  }, 500);
});

$(document).on("click", ".slide-toggle-visibility", function () {
  const button = $(this);
  const HomeID = button.data("id");
  const newStatus = button.data("status") === 1 ? 0 : 1;

  fetch("update_slide_status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      HomeID: HomeID,
      HomeStatus: newStatus,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Server Response:", data);
      if (data.success) {
        // เปลี่ยนสีปุ่มและข้อความ
        button.toggleClass("btn-success btn-secondary");
        button.data("status", newStatus);
        button.html(
          newStatus === 1
            ? '<i class="fa-solid fa-eye"></i> แสดง'
            : '<i class="fa-solid fa-eye-slash"></i> ซ่อน'
        );

        Swal.fire({
          title: "เปลี่ยนสถานะสำเร็จ!",
          text:
            newStatus === 1
              ? "สถานะถูกเปลี่ยนเป็น 'แสดง'"
              : "สถานะถูกเปลี่ยนเป็น 'ซ่อน'",
          icon: "success",
          confirmButtonText: "ตกลง",
        });
      } else {
        Swal.fire({
          title: "เกิดข้อผิดพลาด!",
          text: "ไม่สามารถเปลี่ยนสถานะได้: " + data.message,
          icon: "error",
          confirmButtonText: "ตกลง",
        });
      }
    })
    .catch((error) => {
      console.error("Fetch Error:", error);
      Swal.fire({
        title: "ข้อผิดพลาด!",
        text: "ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์",
        icon: "error",
        confirmButtonText: "ตกลง",
      });
    });
});

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

$("#sortSlidesBtn").click(function () {
  let sortData = [];
  $(".sort-input").each(function () {
    let homeID = $(this).data("id");
    let homeSort = $(this).val();
    sortData.push({ HomeID: homeID, HomeSort: homeSort });
  });

  $.ajax({
    url: "update_slide_sort.php",
    type: "POST",
    data: { sortData: JSON.stringify(sortData) },
    success: function () {
      Swal.fire("สำเร็จ!", "จัดเรียงรูป Slide สำเร็จ", "success").then(() => {
        location.reload();
      });
    },
  });
});
