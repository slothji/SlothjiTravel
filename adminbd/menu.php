<div class="sidebar">
    <div class="top">
        <div class="logo">
            <span class="">DashBoard</span>
        </div>
        <i class="fa-solid fa-bars" id="btn"></i>
    </div>
    <div class="user">
        <img src="../image/profile.jpg" alt="slothji" class="user-img">
        <div>
            <p class="bold"><?php echo htmlspecialchars($_SESSION['AdminUserName']); ?></p>
            <p>Admin</p>
        </div>
    </div>
    <ul class="ms-0 ps-0">
        <li>
            <a href="adminbackdoor.php">
                <i class="fa-solid fa-house"></i>
                <span class="nav-item">Main</span>
            </a>
            <span class="tooltip">Main</span>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-location-dot"></i>
                <span class="nav-item">Place</span>
            </a>
            <span class="tooltip">Place</span>
        </li>
        <li>
            <a href="admintype.php">
                <i class="fa-solid fa-compass"></i>
                <span class="nav-item">TypePlace</span>
            </a>
            <span class="tooltip">TypePlace</span>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-star"></i>
                <span class="nav-item">Review</span>
            </a>
            <span class="tooltip">Review</span>
        </li>
        <li>
            <a href="adminlogout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="nav-item">Logout</span>
            </a>
            <span class="tooltip">Logout</span>
        </li>
    </ul>
</div>