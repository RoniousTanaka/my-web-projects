<?php
session_start();
include 'connectiondb.php';

// Ensure user is logged in
if (!isset($_SESSION['regnumber'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['regnumber'];

// --- Get Student Name ---
$studentName = "Student";
$nameQuery = "SELECT fullname FROM users WHERE regnumber = ?";
$nameStmt = $conn->prepare($nameQuery);
$nameStmt->bind_param("s", $user_id);
$nameStmt->execute();
$nameResult = $nameStmt->get_result();
if ($row = $nameResult->fetch_assoc()) {
    $studentName = htmlspecialchars($row['fullname']);
}
$nameStmt->close();

// --- Calculate CV Completion Percentage ---
$cvCompleted = 0;
$cvQuery = "SELECT * FROM cv_data1 WHERE full_name = ?";  // Changed fullname to full_name to match your table
$cvStmt = $conn->prepare($cvQuery);
$cvStmt->bind_param("s", $studentName);  // Changed $user_id to $studentName to match your variable
$cvStmt->execute();
$cvResult = $cvStmt->get_result();

if ($cvResult->num_rows > 0) {  // Check if any rows were returned first
    $cvRow = $cvResult->fetch_assoc();
    
    // List of fields to check for completion
    $fieldsToCheck = [
        'full_name', 'email', 'linkedin', 'physical_address', 'phone_number',
        'personal_statement', 'university', 'program', 'expected_graduation',
        'certifications', 'skills', 'interests', 'semester_results'
    ];
    
    $filledFields = 0;
    $totalFields = count($fieldsToCheck);
    
    foreach ($fieldsToCheck as $field) {
        if (!empty($cvRow[$field])) {
            $filledFields++;
        }
    }
    
    $cvCompleted = round(($filledFields / $totalFields) * 100);
}
$cvStmt->close();

// --- Fetch latest application status ---
$app_status = "No application yet";
$appQuery = "SELECT status FROM applications WHERE student_id = ? ORDER BY created_at DESC LIMIT 1";
$appStmt = $conn->prepare($appQuery);
$appStmt->bind_param("s", $user_id);
$appStmt->execute();
$appResult = $appStmt->get_result();
if ($row = $appResult->fetch_assoc()) {
    $app_status = htmlspecialchars($row['status']);
}
$appStmt->close();

// --- Fetch notifications ---
$notifications = [];
$notifQuery = "SELECT message FROM notifications WHERE student_id = ? ORDER BY created_at DESC LIMIT 5";
$notifStmt = $conn->prepare($notifQuery);

$notifStmt->close();

// --- Time Ago Function ---
function time_ago($datetime) {
    $time_ago = strtotime($datetime);
    $current_time = time();
    $time_difference = $current_time - $time_ago;

    if ($time_difference <= 60) return "Just now";
    elseif ($time_difference < 3600) return round($time_difference / 60) . " minutes ago";
    elseif ($time_difference < 86400) return round($time_difference / 3600) . " hours ago";
    elseif ($time_difference < 604800) return round($time_difference / 86400) . " days ago";
    elseif ($time_difference < 2629440) return round($time_difference / 604800) . " weeks ago";
    elseif ($time_difference < 31553280) return round($time_difference / 2629440) . " months ago";
    else return round($time_difference / 31553280) . " years ago";
}
?>
<style>
    
</style>
<!-- HTML Dashboard -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        nav a { margin-right: 10px; }
        .card, .notification-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; }
        .status { font-weight: bold; color: #007BFF; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav>
    <a href="uploadedCV.php">Your CV</a> |
    <a href="profile_dashboard.php">Your Profile</a> |
  
</nav>

<!-- Dashboard Content -->
<div class="contentArea">
    <h2>Welcome to your Dashboard</h2>
    <p><strong>Hi <?php echo $studentName; ?> 👋</strong></p>

    <!-- Info Cards -->
    <div class="info-cards">
        <div class="card">
            <h3>📄 CV Completion</h3>
            <p>Your CV is <strong><?php echo $cvCompleted; ?>%</strong> complete. <a href="cvmaker.php">Update CV</a></p>
        </div>
        <div class="card">
            <h3>📬 Application Status</h3>
            <p>Current internship status: <span class="status"><?php echo $app_status; ?></span></p>
        </div>
    </div>

    <!-- Notifications -->
    <div class="notifications">
        <h3>🔔 Recent Notifications</h3>
        <?php if (count($notifications) === 0): ?>
            <p>No new notifications.</p>
        <?php else: ?>
            <?php foreach ($notifications as $note): ?>
                <div class="notification-item">
                    <strong><?php echo htmlspecialchars($note['message']); ?></strong>
                    <div><small><?php echo time_ago($note['created_at']); ?></small></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Intern Recruitment System | <a href="#">Privacy Policy</a></p>
</footer>

</body>
</html>
