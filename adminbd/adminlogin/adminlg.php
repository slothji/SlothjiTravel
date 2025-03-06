<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['AdminUserName']);
    $password = $_POST['AdminPassword'];

    $sql = "SELECT * FROM admins WHERE AdminUserName = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['AdminPassword'])) {
            $_SESSION['AdminID'] = $user['AdminID'];
            $_SESSION['AdminUserName'] = $user['AdminUserName'];

            echo '1';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                Swal.fire({
                    title: "Login Successful!",
                    text: "Welcome ' . $user['AdminUserName'] . '!",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        window.location.href = "../adminbackdoor.php";
                    }
                });
            </script>';
            exit();
        } else {
            echo '2';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                Swal.fire({
                    title: "Error!",
                    text: "Invalid password.",
                    icon: "error",
                    confirmButtonText: "Try Again"
                }).then(() => {
                    window.location.href = "adminlogin.php";
                });
            </script>';
            exit();
        }
    } else {
        echo '3';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                title: "Error!",
                text: "Username not found.",
                icon: "error",
                confirmButtonText: "Try Again"
            }).then(() => {
                window.location.href = "adminlogin.php";
            });
        </script>';
        exit();
    }
}
