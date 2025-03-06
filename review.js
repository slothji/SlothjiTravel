const allStar = document.querySelectorAll(".rating .mystar");

allStar.forEach((item, idx) => {
  item.addEventListener("click", function () {
    allStar.forEach((i) => {
      i.classList.replace("fa-solid", "fa-regular");
    });

    for (let i = 0; i <= idx; i++) {
      allStar[i].classList.replace("fa-regular", "fa-solid");
    }
  });
});
