<?php
// Enable error reporting for debugging
error_reporting(0);
ini_set('display_errors', 1);

// Start the session
session_start();

// Database connection
$host = "localhost";
$dbname = "interndb"; // Replace with your database name
$username = "root"; // Replace with your username
$password = ""; // Replace with your password

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check session variables
if (isset($_SESSION['regnumber'])) {
    $regnumber = $_SESSION['regnumber'];


    // Fetch the student's name
    $sql = "SELECT fullname FROM users WHERE regnumber = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Failed to prepare SQL statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "s", $regnumber);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $studentName);

    // Check if a result is found
    if (mysqli_stmt_fetch($stmt)) {
        $studentName = htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); // Sanitize the name
    } else {
        $studentName = "Guest"; // Default value if no user is found
    }
    mysqli_stmt_close($stmt);
} else {
    die("Session variable regnumber is not set.");
}

// Close the database connection
mysqli_close($conn);
?>
