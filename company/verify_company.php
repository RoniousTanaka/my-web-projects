<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $passcode = $_POST['passcode'] ?? '';

    if ($email && $passcode) {
        $stmt = $conn->prepare("SELECT * FROM company_passcodes WHERE email = ? AND passcode = ? AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("ss", $email, $passcode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Success! Log the company in
            $_SESSION['company_email'] = $email;
            echo "✅ Passcode verified. Redirecting...";

            // Redirect to dashboard
            header("refresh:2; url=header.php");
            exit;
        } else {
            echo "❌ Invalid or expired passcode.";
        }
    } else {
        echo "❌ Email and passcode are required.";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Passcode | INTERNREC</title>
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

        input[type="email"],
        input[type="text"] {
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
            <header>Verify Passcode</header>
            <form method="post">
                <input type="email" name="email" placeholder="Enter your company email" required>
                <input type="text" name="passcode" placeholder="Enter the passcode" required>
                <button class="button" type="submit">Verify if You registered</button>
            </form>
        </div>
    </div>
</body>

</html>
