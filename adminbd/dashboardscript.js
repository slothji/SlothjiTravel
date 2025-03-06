document.addEventListener("DOMContentLoaded", function () {
  const filterButtons = document.querySelectorAll(".filter-btn");
  const monthSelector = document.getElementById("monthSelector");
  const dateInput = document.getElementById("dateInput");
  const yearSelector = document.getElementById("yearSelector");
  const dateRangeContainer = document.getElementById("dateRangeContainer");
  const searchForm = document.getElementById("searchForm");
  const startDateInput = document.getElementById("startDate");
  const endDateInput = document.getElementById("endDate");

  let currentFilter = "all";

  // ตั้งค่าสำหรับฟิลด์วันที่ให้เลือกได้แค่ถึงวันนี้
  const today = new Date().toISOString().split("T")[0];
  startDateInput.setAttribute("max", today);
  endDateInput.setAttribute("max", today);

  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      filterButtons.forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");
      currentFilter = this.getAttribute("data-filter");

      if (currentFilter === "monthly") {
        monthSelector.style.display = "block";
        dateInput.style.display = "none";
        yearSelector.style.display = "none";
        dateRangeContainer.style.display = "none";
      } else if (currentFilter === "daily") {
        monthSelector.style.display = "none";
        dateInput.style.display = "block";
        yearSelector.style.display = "none";
        dateRangeContainer.style.display = "none";
      } else if (currentFilter === "yearly") {
        monthSelector.style.display = "none";
        dateInput.style.display = "none";
        yearSelector.style.display = "block";
        dateRangeContainer.style.display = "none";
      } else if (currentFilter === "date-range") {
        monthSelector.style.display = "none";
        dateInput.style.display = "none";
        yearSelector.style.display = "none";
        dateRangeContainer.style.display = "block";
      } else {
        monthSelector.style.display = "none";
        dateInput.style.display = "none";
        yearSelector.style.display = "none";
        dateRangeContainer.style.display = "none";
      }

      fetchData();
    });
  });

  searchForm.addEventListener("submit", function (event) {
    event.preventDefault();
    fetchData();
  });

  function fetchData() {
    const month = monthSelector.value;
    const date = dateInput.value;
    const year = yearSelector.value;
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;

    const params = new URLSearchParams({
      filter: currentFilter,
      month: month,
      date: date,
      year: year,
      startDate: startDate,
      endDate: endDate,
    });

    updateSection("visitAllCountSection", "fetchallvisitors.php", params);
    updateSection("visitCountChart", "fetchvisitchart.php", params);
    updateSection("visitCountSection", "fetchvisitcount.php", params);
    updateSection("loginCountSection", "fetchlogincount.php", params);
    updateSection("topVisitedSection", "fetchtopvisited.php", params);
    updateSection("topReviewedSection", "fetchtopreviewed.php", params);
  }

  fetchData();
});

function updateSection(sectionId, url, params) {
  fetch(`${url}?${params.toString()}`)
    .then((response) => response.json())
    .then((data) => {
      const section = document.getElementById(sectionId);
      if (!section) {
        console.error(`Error: Element with ID '${sectionId}' not found.`);
        return;
      }

      if (sectionId === "visitCountChart") {
        renderChart(data);
        return;
      }

      if (sectionId === "visitCountSection") {
        section.innerHTML = data.type
          ? `ประเภท: ${data.type} <br> จำนวนการเข้าชม: ${data.visitCount}`
          : "ขณะนี้ยังไม่มีข้อมูลให้แสดง";
        return;
      }

      if (sectionId === "visitAllCountSection") {
        section.innerHTML = data.totalVisits
          ? `จำนวนการเข้าชมทั้งหมด: ${data.totalVisits} ครั้ง`
          : "ขณะนี้ยังไม่มีข้อมูลให้แสดง";
        return;
      }

      if (sectionId === "loginCountSection") {
        section.innerHTML = data.totalLogins
          ? `จำนวนผู้เข้าสู่ระบบทั้งหมด: ${data.totalLogins}`
          : "ขณะนี้ยังไม่มีข้อมูลให้แสดง";
        return;
      }

      if (
        sectionId === "topVisitedSection" ||
        sectionId === "topReviewedSection"
      ) {
        let titleText =
          sectionId === "topReviewedSection"
            ? "สถานที่ที่มีคนรีวิวมากที่สุด 5 อันดับ"
            : "สถานที่ที่มีคนเข้าชมมากที่สุด 5 อันดับ";

        if (data.length > 0) {
          let tableHtml = `
                <div class="text-center mb-3">
                    <h4 class="mt-2 mb-2">${titleText}</h4> 
                </div>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">อันดับ</th>
                            <th class="text-center">สถานที่</th>
                            <th class="text-center">${
                              sectionId === "topReviewedSection"
                                ? "จำนวนการรีวิว"
                                : "จำนวนการเข้าชม"
                            }</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

          data.forEach((item, index) => {
            tableHtml += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="./placepage/uploads/places/${
                                  item.PlaceImg
                                }" 
                                    alt="${
                                      item.PlaceTitle
                                    }" class="rounded" width="60" height="60" 
                                    style="object-fit: cover; margin-right: 10px;">
                                ${item.PlaceTitle}
                            </div>
                        </td>
                        <td class="text-center">${
                          item.total_reviews || item.total_visits
                        }</td>
                    </tr>
                `;
          });

          tableHtml += "</tbody></table>";
          section.innerHTML = tableHtml;
        } else {
          section.innerHTML = `<p class="text-center text-muted">ขณะนี้ยังไม่มีข้อมูลให้แสดง</p>`;
        }
        return;
      }

      section.innerHTML = data;
    })
    .catch((error) =>
      console.error(`Error loading data for ${sectionId}:`, error)
    );
}

function renderChart(data) {
  let ctx = document.getElementById("visitCountChart");

  if (!ctx) {
    console.error("Error: Element with ID 'visitCountChart' not found.");
    return;
  }

  ctx = ctx.getContext("2d");

  if (!ctx) {
    console.error("Error: Unable to acquire context for the chart.");
    return;
  }

  let labels = data.map((item) => item.TypeTitle);
  let visitCounts = data.map((item) => item.TotalVisitCount);

  if (window.myChart) {
    window.myChart.destroy();
  }

  window.myChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          data: visitCounts,
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"],
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },

        datalabels: {
          anchor: "end",
          align: "top",
          color: "#000",
          font: {
            weight: "bold",
            size: 14,
          },
          formatter: (value) => value,
        },
      },
    },
    plugins: [ChartDataLabels],
  });
}
