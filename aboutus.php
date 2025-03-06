<?php
include 'dataDB.php';
session_start();

if (!isset($_SESSION['redirect_url'])) {
  $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}


$sql = "SELECT * FROM aboutus";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  if (
    empty($row['AboutImg']) && empty($row['AboutTitle']) && empty($row['AboutProfile']) &&
    empty($row['AboutDetail']) && empty($row['AboutSubTitle']) && empty($row['AboutSubDetail'])
  ) {

    header('Location: index.php');
    exit;
  }
} else {

  header('Location: index.php');
  exit;
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
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <title>ABOUTUS</title>
</head>


<body>

  <?php include 'navbar.php'; ?>
  <section class="locate-group p-0 m-0">
    <div>
      <div class="container-fluid p-0 m-0">
        <div class="about-group">
          <img src="./adminbd/aboutus/uploads/about_img/<?php echo htmlspecialchars($row['AboutImg']); ?>" class="aboutBG d-block w-100" data-aos="fade-in">
          <div class="about-title">
            <div class="title-we">
              <h1 data-aos="fade-up"><?php echo htmlspecialchars($row['AboutTitle']); ?></h1>
            </div>
            <div class="row">
              <div class="col-12">
                <img src="./adminbd/aboutus/uploads/about_profile/<?php echo htmlspecialchars($row['AboutProfile']); ?>" class="myprofile" data-aos="fade-up">
                <div class="abouttext-scroll">
                  <p data-aos="fade-up"><?php echo nl2br(htmlspecialchars($row['AboutDetail'])); ?></p>
                  <h3 data-aos="fade-up"><?php echo htmlspecialchars($row['AboutSubTitle']); ?></h3>
                  <p data-aos="fade-up"><?php echo nl2br(htmlspecialchars($row['AboutSubDetail'])); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php include 'footer.php' ?>

  </section>
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
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      function handleOverflowScroll() {
        const targetDiv = document.querySelector(".abouttext-scroll");
        if (targetDiv) {
          if (window.innerWidth < 768) {
            targetDiv.classList.add("overflow-scroll");
          } else {
            targetDiv.classList.remove("overflow-scroll");
          }
        }
      }


      handleOverflowScroll();


      window.addEventListener("resize", handleOverflowScroll);
    });
  </script>
</body>

</html>