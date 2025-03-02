fetch("typePlaces.json")
  .then((response) => response.json())
  .then((typePlaces) => {
    const container = document.querySelector(".row.mb-5");
    typePlaces.forEach((place) => {
      const boxItem = document.createElement("div");
      boxItem.className = "box-item col-12 col-sm-12 col-md-6 col-lg-3 mb-3";
      boxItem.innerHTML = `
          <div class="flip-box">
            <div class="flip-box-front text-center" style="background-image: url('${place.image}');">
              <div class="inner colo-white">
                <h3 class="flip-box-header">${place.title}</h3>
              </div>
            </div>
            <div class="flip-box-back text-center" style="background-image: url('${place.image}');">
              <div class="inner colo-white">
                <p>${place.description}</p>
                <button class="flip-box-button">
                  <a class="flip-btn-text" href="${place.link}">Let's Go!</a>
                </button>
              </div>
            </div>
          </div>
        `;
      container.appendChild(boxItem);
    });
  })
  .catch((error) => console.error("Error fetching JSON:", error));
