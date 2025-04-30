<?php
session_start();
include('connectiondb.php'); // Ensure this is the correct path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];

    // Step 1: Mark application as selected
    $stmt = $conn->prepare("UPDATE applications SET selected = 'yes' WHERE id = ?");
    $stmt->bind_param("i", $application_id);
    $stmt->execute();

    // Step 2: Get student_id from the application
    $stmt2 = $conn->prepare("SELECT student_id FROM applicants WHERE id = ?");
    $stmt2->bind_param("i", $application_id);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $application = $result->fetch_assoc();

    // Step 3: Update the student's has_internship field
    if ($application && isset($application['student_id'])) {
        $student_id = $application['student_id'];

        $stmt3 = $conn->prepare("UPDATE students SET has_internship = 'yes' WHERE id = ?");
        $stmt3->bind_param("i", $student_id);
        $stmt3->execute();
    }
}

// Redirect back to your review page
header("Location: verification_ofIntern.php");
exit();
?>

