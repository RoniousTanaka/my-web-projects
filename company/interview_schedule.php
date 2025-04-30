<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

// Database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $intern_id = $_POST['intern_id'];
    $interview_date = $_POST['interview_date'];

    // Check if the intern exists
    $stmt = $pdo->prepare("SELECT * FROM interns WHERE id = :intern_id");
    $stmt->bindParam(':intern_id', $intern_id);
    $stmt->execute();
    $intern = $stmt->fetch();

    if ($intern) {
        // Update the interview date
        $stmt = $pdo->prepare("UPDATE interns SET interview_date = :interview_date WHERE id = :intern_id");
        $stmt->bindParam(':interview_date', $interview_date);
        $stmt->bindParam(':intern_id', $intern_id);

        if ($stmt->execute()) {
            // Sending email to the intern using PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Use your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'h230725y@hit.ac.zw';  // Your Gmail address
                $mail->Password = 'epcxsfyrzknsargz';  // Your Gmail app password (or email password)
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Sender email
                $mail->setFrom('internreczim@gmail.com', 'Internship Recruitment System (internrec)');

                // Get the recipient email dynamically from the intern table
                $mail->addAddress($intern['email'], $intern['name']);  // Intern email and name

                // Content of the email
                $mail->isHTML(true);
                $mail->Subject = 'Interview Scheduled';
                $mail->Body    = "Dear {$intern['name']},<br><br>Your interview has been scheduled for {$interview_date}.<br><br>Best regards,<br>The Team";

                // Send email
                $mail->send();
                echo 'Interview scheduled and email sent successfully.';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error scheduling interview.";
        }
    } else {
        echo "Intern not found.";
    }
}
?>

<form method="POST">
    <label for="intern_id">Select Intern:</label><br>
    <select name="intern_id">
        <?php
        // Fetch all interns to display in the dropdown list
        $stmt = $pdo->query("SELECT * FROM interns WHERE interview_status = 'Scheduled'");
        while ($row = $stmt->fetch()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select><br>

    <label for="interview_date">Interview Date:</label><br>
    <input type="datetime-local" name="interview_date" required><br>

    <button type="submit">Schedule Interview</button>
</form>
