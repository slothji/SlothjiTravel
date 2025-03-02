const allStar = document.querySelectorAll(".rating .mystar");

allStar.forEach((item, idx) => {
  item.addEventListener("click", function () {
    // รีเซ็ตดาวทั้งหมดให้กลับไปเป็น "fa-regular fa-star"
    allStar.forEach((i) => {
      i.classList.replace("fa-solid", "fa-regular");
    });

    // ตั้งค่าดาวให้เป็น "fa-solid fa-star" จนถึงดาวที่ถูกคลิก
    for (let i = 0; i <= idx; i++) {
      allStar[i].classList.replace("fa-regular", "fa-solid");
    }
  });
});
