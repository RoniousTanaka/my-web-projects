<?php
session_start();
include('connectiondb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $flag = $_POST['flag'];

    if (in_array($flag, ['red', 'yellow', 'green'])) {
        $stmt = $conn->prepare("UPDATE applications SET status_flag = ? WHERE id = ?");
        $stmt->bind_param("si", $flag, $application_id);
        $stmt->execute();
    }
}

header("Location: review_applicants.php");
exit();
