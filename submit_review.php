<?php
session_start();
include 'dataDB.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $placeID = isset($_POST['placeID']) ? (int)$_POST['placeID'] : 0;
        $userID = isset($_POST['userID']) ? (int)$_POST['userID'] : (isset($_SESSION['UserID']) ? (int)$_SESSION['UserID'] : 0);
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
        $reviewDate = date('Y-m-d H:i:s');

        if ($placeID > 0 && $userID > 0 && $rating >= 1 && $rating <= 5 && !empty($comment)) {
            $sql = "INSERT INTO reviews (PlaceID, UserID, Rating, Comment) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("iiis", $placeID, $userID, $rating, $comment);
                if ($stmt->execute()) {
                    $response = [
                        'success' => 1,
                        'msg' => 'Review added successfully',
                        'placeID' => $placeID,
                        'ts' => date('YmdHis'),
                    ];
                } else {
                    $response = [
                        'success' => 0,
                        'msg' => 'Failed to execute query',
                        'ts' => date('YmdHis'),
                    ];
                }
                $stmt->close();
            } else {
                $response['msg'] = 'Failed to prepare statement: ' . $conn->error;
            }
        } else {
            $response['msg'] = 'Invalid input data';
        }
    } else {
        $response['msg'] = 'Invalid request method';
    }
} catch (Exception $e) {
    $response['msg'] = 'An error occurred: ' . $e->getMessage();
}


$json_response = json_encode($response);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON Encoding Error: " . json_last_error_msg());
    $json_response = json_encode([
        'success' => 0,
        'msg' => 'Internal Server Error: JSON encoding failed',
    ]);
}

ob_clean();
echo $json_response;
exit;
