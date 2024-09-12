<?php
include('../includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_id = intval($_POST['feedback_id']);
    $status = $_POST['status'];

    $allowed_statuses = ['pending', 'reviewed', 'resolved'];
    if (!in_array($status, $allowed_statuses)) {
        die('Invalid status');
    }

    $query = "UPDATE feedback SET status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('si', $status, $feedback_id);

    if ($stmt->execute()) {
        header('Location: feedback.php');
        exit();
    } else {
        echo 'Error updating status';
    }

    $stmt->close();
}

$con->close();
?>
