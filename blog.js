document.addEventListener("DOMContentLoaded", () => {
  const allStar = document.querySelectorAll(".rating .mystar");
  const ratingValue = document.querySelector(".rating input");
  const commentField = document.querySelector(".rating-comment");
  const submitButton = document.querySelector("#submitReview");

  ratingValue.value = 0;
  let rating = 0;

  function updateRatingDisplay() {
    console.log("Current rating: ", rating);
  }

  allStar.forEach((star, idx) => {
    star.addEventListener("click", () => {
      ratingValue.value = idx + 1;
      rating = ratingValue.value;

      updateRatingDisplay();

      allStar.forEach((s, i) => {
        s.classList.toggle("active", i <= idx);
        s.classList.replace(
          i <= idx ? "bx-star" : "bxs-star",
          i <= idx ? "bxs-star" : "bx-star"
        );
      });
    });
  });

  submitButton.addEventListener("click", () => {
    const comment = commentField.value.trim();
    const placeID = document.querySelector("input[name='PlaceID']").value;
    const userID = document.querySelector("input[name='UserID']").value;

    if (!placeID || !userID || !rating || !comment) {
      if (!userID) {
        Swal.fire({
          icon: "error",
          title: "ไม่พบบัญชีผู้ใช้",
          text: "กรุณาเข้าสู่ระบบก่อน ท่านจึงจะสามารถแสดงความคิดเห็นได้",
        });
        return;
      } else {
        Swal.fire({
          icon: "error",
          title: "กรอกความคิดเห็นไม่ครบ",
          text: "กรุณากรอกความคิดเห็นให้ครบถ้วน",
        });
        return;
      }
    }

    $.ajax({
      url: "submit_review.php",
      type: "POST",
      data: {
        placeID: placeID,
        userID: userID,
        rating: rating,
        comment: comment,
      },
      success: function (res) {
        console.log(res.success);
        if (res.success) {
          Swal.fire({
            icon: "success",
            title: "เขียนรีวิวเรียบร้อย!!",
            text: "ความคิดเห็นของคุณถูกส่งเรียบร้อยแล้ว",
          }).then(() => {
            window.location.assign("blog.php?PlaceID=" + res.placeID);
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Failed to Submit Review",
            text: res.msg || "Something went wrong. Please try again.",
          });
        }
      },
    });
  });

  updateRatingDisplay();
  document.addEventListener("DOMContentLoaded", function () {
    if (window.location.hash && window.location.hash === "#reviewzone") {
      const target = document.querySelector("#reviewzone");
      if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    }
  });
});
