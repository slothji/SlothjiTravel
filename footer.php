<?php
$typesql = "SELECT * FROM typeplace";
$typeresult = $conn->query($typesql);

if ($typeresult->num_rows > 0) {
    // หากมีข้อมูล
    $typeplaces = [];
    while ($row = $typeresult->fetch_assoc()) {
        $typeplaces[] = $row;
    }
} else {
    echo "No data found";
}
?>
<footer class="footer-body">
    <div class="footer-col col-lg-3 col-md-6 col-sm-6">
        <h4>Menu</h4>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="places.php">Places</a></li>
            <li><a href="">Back to top</a></li>
        </ul>
    </div>
    <div class="footer-col col-lg-3 col-md-6 col-sm-6">
        <h4>Places Type</h4>
        <?php
        foreach ($typeplaces as $place): ?>
            <ul>
                <li><a href="/newsloth/places.php?type=<?= $place['TypeID'] ?>"><?= $place['TypeTitle'] ?></a></li>
            </ul>
        <?php endforeach; ?>
    </div>

    <div class="footer-col col-lg-3 col-md-6 col-sm-6">
        <h4>company</h4>
        <ul>
            <li><a href="aboutus.php">about us</a></li>
        </ul>
    </div>
    <div class="footer-col col-lg-3 col-md-6 col-sm-6">
        <h4>follow us</h4>
        <div class="links link-footer">
            <a href="#" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="30px" fill="red"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6-11.4 42.9-11.4 132.3-11.4 132.3s0 89.4 11.4 132.3c6.3 23.7 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zm-317.5 213.5V175.2l142.7 81.2-142.7 81.2z" />
                </svg></a>
            <a href="#" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25px" fill="black"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                </svg></a>
            <a href="#" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30px" fill="#5764ed"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z" />
                </svg></a>
            <a href="#" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30px" fill="gray"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
                </svg></a>
        </div>
    </div>

</footer>