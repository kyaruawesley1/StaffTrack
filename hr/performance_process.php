<?php
session_start();
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $employee_id = $_POST['employee_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $reviewer_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("
        INSERT INTO performance_reviews (employee_id, reviewer_id, rating, feedback)
        VALUES (?, ?, ?, ?)
    ");

    if ($stmt->execute([$employee_id, $reviewer_id, $rating, $comment])) {
        header("Location: performance.php?success=1");
    } else {
        header("Location: performance.php?error=1");
    }
    exit();
}
?>
