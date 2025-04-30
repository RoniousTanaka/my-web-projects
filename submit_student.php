<?php
include 'db_connection.php'; // Your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['student_name'];
    $email = $_POST['student_email'];
    $token = bin2hex(random_bytes(16)); // Generate unique token

    // Store data in the temporary table
    $query = "INSERT INTO temp_students (name, email, token) VALUES ('$name', '$email', '$token')";
    mysqli_query($conn, $query);

    // Send the email
    $verify_link = "http://yourwebsite.com/verify.php?token=$token";
    $subject = "Email Verification";
    $message = "Click the link to verify your email and save your details: $verify_link";
    $headers = "From: no-reply@yourwebsite.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "An email has been sent to $email. Please check your inbox.";
    } else {
        echo "Failed to send verification email.";
    }
}
?>
