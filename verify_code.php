<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInputCode = $_POST['code'];

    if (isset($_SESSION['verification_code']) && $_SESSION['verification_code'] === $userInputCode) {
        echo "code_verified";
    } else {
        echo "invalid_code";
    }
}
