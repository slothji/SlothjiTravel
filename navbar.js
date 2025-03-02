window.addEventListener("scroll", function () {
  let navbar = document.querySelector(".navbar");
  if (window.scrollY > 50) {
    navbar.style.position = "fixed"; // ทำให้ navbar ล็อกอยู่ด้านบน
    navbar.style.top = "0";
  } else {
    navbar.style.position = "absolute"; // กลับไปเป็น absolute ถ้าเลื่อนขึ้นไปด้านบน
    navbar.style.backgroundColor = "transparent"; // คืนค่าโปร่งใส
  }
});

const signinbtnLink = document.querySelector(".signinbtn-link");
const signupbtnLink = document.querySelector(".signupbtn-link");
const loginwrapper = document.querySelector(".login-wrapper");

signupbtnLink.addEventListener("click", () => {
  loginwrapper.classList.toggle("active");
});

signinbtnLink.addEventListener("click", () => {
  loginwrapper.classList.toggle("active");
});

document.addEventListener("DOMContentLoaded", () => {
  const navE1 = document.querySelector(".navbar");
  window.addEventListener("scroll", () => {
    if (navE1 && window.scrollY > 56) {
      navE1.classList.add("navbar-scrolled");
    } else if (navE1) {
      navE1.classList.remove("navbar-scrolled");
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const navbarToggler = document.querySelector(".navbar-toggler");
  const navbarCollapse = document.querySelector(".navbar-collapse");

  navbarToggler.addEventListener("click", () => {
    // Toggle 'show' class for navbar-collapse
    navbarCollapse.classList.toggle("show");

    // Apply or remove backdrop styles
    if (navbarCollapse.classList.contains("show")) {
      navbarCollapse.style.backgroundColor = "rgba(27, 40, 12, 0.7)";
      navbarCollapse.style.backdropFilter = "blur(15px)";
    } else {
      navbarCollapse.style.backgroundColor = "";
      navbarCollapse.style.backdropFilter = "";
    }
  });

  // Optionally: Close menu when clicking outside (optional)
  document.addEventListener("click", (event) => {
    if (
      !navbarToggler.contains(event.target) && // Clicked outside toggler
      !navbarCollapse.contains(event.target) // Clicked outside menu
    ) {
      navbarCollapse.classList.remove("show");
      navbarCollapse.style.backgroundColor = "";
      navbarCollapse.style.backdropFilter = "";
    }
  });
});

$(document).ready(function () {
  var itemsMainDiv = ".MultiCarousel";
  var itemsDiv = ".MultiCarousel-inner";
  var itemWidth = "";

  $(".leftLst, .rightLst").click(function () {
    var condition = $(this).hasClass("leftLst");
    if (condition) click(0, this);
    else click(1, this);
  });

  ResCarouselSize();

  $(window).resize(function () {
    ResCarouselSize();
  });

  //this function define the size of the items
  function ResCarouselSize() {
    var incno = 0;
    var dataItems = "data-items";
    var itemClass = ".item";
    var id = 0;
    var btnParentSb = "";
    var itemsSplit = "";
    var sampwidth = $(itemsMainDiv).width();
    var bodyWidth = $("body").width();
    $(itemsDiv).each(function () {
      id = id + 1;
      var itemNumbers = $(this).find(itemClass).length;
      btnParentSb = $(this).parent().attr(dataItems);
      itemsSplit = btnParentSb.split(",");
      $(this)
        .parent()
        .attr("id", "MultiCarousel" + id);

      if (bodyWidth >= 1200) {
        incno = itemsSplit[3];
        itemWidth = sampwidth / incno;
      } else if (bodyWidth >= 992) {
        incno = itemsSplit[2];
        itemWidth = sampwidth / incno;
      } else if (bodyWidth >= 768) {
        incno = itemsSplit[1];
        itemWidth = sampwidth / incno;
      } else {
        incno = itemsSplit[0];
        itemWidth = sampwidth / incno;
      }
      $(this).css({
        transform: "translateX(0px)",
        width: itemWidth * itemNumbers,
      });
      $(this)
        .find(itemClass)
        .each(function () {
          $(this).outerWidth(itemWidth);
        });

      $(".leftLst").addClass("over");
      $(".rightLst").removeClass("over");
    });
  }

  //this function used to move the items
  function ResCarousel(e, el, s) {
    var leftBtn = ".leftLst";
    var rightBtn = ".rightLst";
    var translateXval = "";
    var divStyle = $(el + " " + itemsDiv).css("transform");
    var values = divStyle.match(/-?[\d\.]+/g);
    var xds = Math.abs(values[4]);
    if (e == 0) {
      translateXval = parseInt(xds) - parseInt(itemWidth * s);
      $(el + " " + rightBtn).removeClass("over");

      if (translateXval <= itemWidth / 2) {
        translateXval = 0;
        $(el + " " + leftBtn).addClass("over");
      }
    } else if (e == 1) {
      var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
      translateXval = parseInt(xds) + parseInt(itemWidth * s);
      $(el + " " + leftBtn).removeClass("over");

      if (translateXval >= itemsCondition - itemWidth / 2) {
        translateXval = itemsCondition;
        $(el + " " + rightBtn).addClass("over");
      }
    }
    $(el + " " + itemsDiv).css(
      "transform",
      "translateX(" + -translateXval + "px)"
    );
  }

  //It is used to get some elements from btn
  function click(ell, ee) {
    var Parent = "#" + $(ee).parent().attr("id");
    var slide = $(Parent).attr("data-slide");
    ResCarousel(ell, Parent, slide);
  }
});

document.addEventListener("DOMContentLoaded", () => {
  // Get the current URL
  const currentPath = window.location.pathname
    .split("/")
    .pop()
    .replace(".php", "");

  // Get all navbar links
  const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

  // Loop through each link
  navLinks.forEach((link) => {
    // Extract the href attribute and match it with the current path
    const linkPath = link
      .getAttribute("href")
      .split("/")
      .pop()
      .replace(".php", "");
    if (
      linkPath === currentPath ||
      (currentPath === "" && linkPath === "index")
    ) {
      // Add 'active' class if the path matches
      link.classList.add("active");
    } else {
      // Remove 'active' class if the path does not match
      link.classList.remove("active");
    }
  });
});

$(document).ready(function () {
  // ฟังก์ชันลบ modal backdrop
  function removeModalBackdrop() {
    $(".modal-backdrop").remove(); // ลบ backdrop
    $("body").removeClass("modal-open"); // ป้องกัน body ติด scroll-lock
  }

  // จัดการปิด modal ทุกตัวให้ปิด backdrop ด้วย
  $(".modal").on("hidden.bs.modal", function () {
    removeModalBackdrop();
  });

  // ปิด Modal และลบ backdrop เมื่อกดปุ่ม Close (X)
  $(".btn-close").click(function () {
    $(this).closest(".modal").modal("hide"); // ปิด modal
    setTimeout(removeModalBackdrop, 500); // ลบ backdrop หลังปิด modal
  });

  // เมื่อคลิกปิด modal backdrop
  $(document).on("click", ".modal-backdrop", function () {
    removeModalBackdrop();
  });

  // เปิด Modal ยืนยันตัวตน เมื่อกด Forgot Password
  $("#forgotPasswordBtn").click(function () {
    $("#staticBackdrop").modal("hide");
    setTimeout(() => {
      $("#verifyIdentityModal").modal("show");
    }, 500);
  });

  // ตรวจสอบข้อมูลในฐานข้อมูล
  $("#verifyAccountBtn").click(function () {
    var username = $("#verifyUsername").val();
    var email = $("#verifyEmail").val();

    if (username === "" || email === "") {
      Swal.fire("Error", "Please fill in all fields", "error");
      return;
    }

    $.ajax({
      url: "check_user.php",
      type: "POST",
      data: { UserName: username, Emails: email },
      success: function (response) {
        if (response === "not_found") {
          Swal.fire("Error", "User not found", "error");
        } else {
          Swal.fire({
            title: "Success",
            text: "Account found. Proceed to reset password.",
            icon: "success",
          }).then(() => {
            $("#verifyIdentityModal").modal("hide");
            setTimeout(() => {
              $("#resetUsername").val(username);
              $("#resetEmail").val(email);
              $("#resetPasswordModal").modal("show");
            }, 500);
          });
        }
      },
    });
  });

  // รีเซ็ตรหัสผ่าน
  $("#resetPasswordBtn").click(function () {
    var username = $("#resetUsername").val();
    var newPassword = $("#newPassword").val();
    var confirmPassword = $("#confirmPassword").val();

    if (newPassword === "" || confirmPassword === "") {
      Swal.fire("Error", "Please enter a new password", "error");
      return;
    }

    if (newPassword !== confirmPassword) {
      Swal.fire("Error", "Passwords do not match", "error");
      return;
    }

    Swal.fire({
      title: "Confirm",
      text: "Do you want to change your password?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, change it!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "reset_userpass.php",
          type: "POST",
          data: { username: username, newPassword: newPassword },
          beforeSend: function () {
            console.log("Sending Data:", {
              username: username,
              newPassword: newPassword,
            });
          },
          success: function (response) {
            console.log("Server Response:", response);
            if (response.trim() === "success") {
              Swal.fire(
                "Success",
                "Password changed successfully",
                "success"
              ).then(() => {
                $("#resetPasswordModal").modal("hide");
                setTimeout(() => {
                  removeModalBackdrop(); // ลบ backdrop
                  location.reload(); // รีเฟรชหน้า
                }, 500);
              });
            } else {
              Swal.fire("Error", "Something went wrong: " + response, "error");
            }
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            Swal.fire("Error", "AJAX request failed: " + error, "error");
          },
        });
      }
    });
  });
});
