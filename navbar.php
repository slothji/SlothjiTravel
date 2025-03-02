<!-- navbar -->
<?php
include 'dataDB.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบข้อมูลในตาราง aboutus
$sql = "SELECT * FROM aboutus";
$result = $conn->query($sql);

// ตั้งค่าสถานะการแสดงเมนู ABOUT US
$showAboutUs = true; // ค่าเริ่มต้นคือแสดงเมนู
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  // ตรวจสอบว่าทุกช่องในตารางเป็นค่าว่างหรือไม่
  if (
    empty($row['AboutImg']) && empty($row['AboutTitle']) && empty($row['AboutProfile']) &&
    empty($row['AboutDetail']) && empty($row['AboutSubTitle']) && empty($row['AboutSubDetail'])
  ) {
    $showAboutUs = false;
  }
} else {
  // ถ้าไม่มีข้อมูลในฐานข้อมูล
  $showAboutUs = false;
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<nav class="navbar navbar-expand-lg navbar-light p-0">
  <div class="container">
    <img
      class="navbar-brand me-auto"
      src="image/Slothji.png"
      style="width: 50px; height: auto; border-radius: 50%;"
      href="index.php" />
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a id="index" class="nav-link" href="index.php">HOME</a>
        </li>
        <?php if ($showAboutUs): ?>
          <li class="nav-item">
            <a class="nav-link" href="aboutus.php">ABOUT US</a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="places.php?type=1">PLACES</a>
        </li>

        <?php if (isset($_SESSION['UserName'])): ?>
          <li class="nav-item">
            <a class="nav-link">Welcome, <?php echo $_SESSION['UserName']; ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">LOG OUT</a>
          </li>
        <?php else: ?>
          <li>
            <button type="button" class="login-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              <a class="nav-link">ACCOUNT</a>
            </button>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>



<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content login-content">
      <div class="modal-body login-body">
        <div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="login-wrapper">
          <!-- Login Form -->
          <div class="form-wrapper sign-in">
            <form action="login.php" method="POST">
              <h2>Login</h2>
              <div class="input-group">
                <input type="text" name="UserName" required>
                <label for="">Username</label>
              </div>
              <div class="input-group">
                <input type="password" name="Passwords" required>
                <label for="">Password</label>
              </div>
              <button type="submit" name="login" class="rgb">Login</button>
              <div class="signup-link">
                <p>Don't have an account? <a href="#" class="signupbtn-link">Sign Up</a></p>
                <p><a href="#" id="forgotPasswordBtn">Forgot Password?</a></p> <!-- Added Forgot Password -->
              </div>
            </form>
          </div>

          <!-- Sign Up Form -->
          <div class="form-wrapper sign-up">
            <form action="signup.php" method="POST">
              <h2 class="mt-5">Sign Up</h2>
              <div class="input-group">
                <input type="text" name="UserName" required>
                <label for="">Username</label>
              </div>
              <div class="input-group">
                <input type="email" name="Emails" required>
                <label for="">Email</label>
              </div>
              <div class="input-group">
                <input type="password" name="Passwords" required>
                <label for="">Password</label>
              </div>
              <div class="g-recaptcha mt-0 mb-2" data-sitekey="6LeR9eQqAAAAAJaSaupV1knGfMBUgWaGZ8ZqbWwb"></div>
              <button type="submit" name="signup" class="rgb">Sign Up</button>
              <div class="signup-link">
                <p>Already have an account? <a href="#" class="signinbtn-link">Sign In</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal ยืนยันตัวตน -->
<div class="modal" id="verifyIdentityModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content login-content">
      <div class="modal-body login-body">
        <div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="login-wrapper">
          <div class="">
            <h2>Verify Identity</h2>
            <div class="input-group">
              <input type="text" id="verifyUsername" required>
              <label>Username</label>
            </div>
            <div class="input-group">
              <input type="email" id="verifyEmail" required>
              <label>Email</label>
            </div>
            <button type="button" class="rgb" id="verifyAccountBtn">Verify Account</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal รีเซ็ตรหัสผ่าน -->
<div class="modal" id="resetPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content login-content">
      <div class="modal-body login-body">
        <div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="login-wrapper">
          <div class="">
            <h2>Reset Password</h2>
            <div class="input-group">
              <input type="text" id="resetUsername" disabled>
              <label></label>
            </div>
            <div class="input-group">
              <input type="email" id="resetEmail" disabled>
              <label></label>
            </div>
            <div class="input-group">
              <input type="password" id="newPassword" required>
              <label>New Password</label>
            </div>
            <div class="input-group">
              <input type="password" id="confirmPassword" required>
              <label>Confirm Password</label>
            </div>
            <button type="button" class="rgb" id="resetPasswordBtn">Change Password</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content login-content">
      <div class="modal-body login-body">
        <div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="login-wrapper">
          <div class="form-wrapper sign-in">
            <form action="login.php" method="POST">
              <h2>Login</h2>
              <div class="input-group">
                <input type="text" name="UserName" required>
                <label for="">Username</label>
              </div>
              <div class="input-group">
                <input type="password" name="Passwords" required>
                <label for="">Password</label>
              </div>
              <button type="submit" name="login" class="rgb">Login</button>
              <div class="signup-link">
                <p>Don't have an account?<a href="#" class="signupbtn-link">Sign Up</a></p>
              </div>
            </form>
          </div>

          <div class="form-wrapper sign-up">
            <form action="signup.php" method="POST">
              <h2>Sign Up</h2>
              <div class="input-group">
                <input type="text" name="UserName" required>
                <label for="">Username</label>
              </div>
              <div class="input-group">
                <input type="email" name="Emails" required>
                <label for="">Email</label>
              </div>
              <div class="input-group">
                <input type="password" name="Passwords" required>
                <label for="">Password</label>
              </div>
              <div class="g-recaptcha" data-sitekey="6LeR9eQqAAAAAJaSaupV1knGfMBUgWaGZ8ZqbWwb"></div>
              <button type="submit" name="signup" class="rgb">Sign Up</button>
              <div class="signup-link">
                <p>Already have an account?<a href="#" class="signinbtn-link">Sign In</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script type="text/javascript"
  src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript" src="navbar.js"></script>