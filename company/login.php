<?php
session_start(); // Start session to store email temporarily

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require 'db.php'; // Optional: if you store passcode in DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_email = $_POST['email'] ?? null;

    if (!$recipient_email) {
        echo "❌ No email provided.";
        exit;
    }

    // Generate 6-digit passcode
    $passcode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Optional: Save to DB for verification later
    $stmt = $conn->prepare("INSERT INTO company_passcodes (email, passcode, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $recipient_email, $passcode, $expires_at);
    $stmt->execute();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'h230725y@hit.ac.zw';
        $mail->Password = 'epcxsfyrzknsargz';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('internreczim@gmail.com', 'Internship Recruitment System (internrec)');
        $mail->addAddress($recipient_email);

        $mail->Subject = 'Your Login Passkey';
        $mail->Body    = "Here is your login passkey: $passcode\nIt will expire in 10 minutes.";

        $mail->send();

        // Store the email in session for verification
        $_SESSION['pending_email'] = $recipient_email;

        // ✅ Redirect to passcode verification page
        header("Location: verify_company.php");
        exit;

    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTERNREC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .bg-img {
            background: url('https://img.freepik.com/premium-photo/laptop-with-bright-neon-outline_1187703-50400.jpg?w=2000') no-repeat center center fixed;
            height: 100vh;
            background-size: cover;
            position: relative;
        }

        .bg-img:after {
            position: absolute;
            content: '';
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 999;
            text-align: center;
            padding: 40px;
            width: 370px;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: -1px 4px 28px rgba(0, 0, 0, 0.75);
            border-radius: 20px;
        }

        .content header {
            color: white;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
            font-family: 'Montserrat', sans-serif;
        }

        input[type="email"] {
            width: 100%;
            height: 50px;
            font-size: 16px;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .button {
            margin-top: 10px;
            background: #333;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .button:hover {
            background: #555;
        }
    </style>
</head>

<body>
    <div class="bg-img">
        <div class="content">
            <header>Send Login Passkey</header>
            <form method="post">
                <input type="email" name="email" placeholder="Enter your company email" required>
                <button class="button" type="submit">Send Passkey</button>
            </form>
        </div>
    </div>
</body>

</html>
