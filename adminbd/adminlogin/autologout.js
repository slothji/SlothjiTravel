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
        window.location.href = "logout.php";
      });
    }, 30 * 60 * 1000);
  }

  document.addEventListener("mousemove", resetTimer);
  document.addEventListener("keypress", resetTimer);
  document.addEventListener("click", resetTimer);

  resetTimer();
});
