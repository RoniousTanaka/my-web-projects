<?php
session_start();
include('db.php');

// Check if DB connection exists
if (!$conn) {
    die("❌ Database connection failed.");
}

$output = ''; // Initialize output variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, htmlspecialchars($_POST['email'])) : '';
    $companyname = isset($_POST['company_name']) ? mysqli_real_escape_string($conn, htmlspecialchars($_POST['company_name'])) : '';

    if (empty($email) || empty($companyname)) {
        $output = "❌ Email and company name are required.";
    } else {
        // Prepare SQL (use prepared statements to prevent SQL injection)
        $stmt = $conn->prepare("SELECT * FROM companies WHERE email_address = ? AND company_name = ?");
        if (!$stmt) {
            $output = "❌ Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $email, $companyname);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Success! Regenerate session ID for security
                session_regenerate_id(true);
                
                // Store company name in session
                $_SESSION['company_name'] = $companyname;
                $_SESSION['email'] = $email;
                
                $output = "✅ Verification successful. Redirecting...";
                header("refresh:2; url=login.php"); // Redirect to dashboard
                exit;
            } else {
                $output = "❌ Invalid company name or email.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTERNREC System | Company Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
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
            transition: background 0.3s;
        }

        .button:hover {
            background: #555;
        }

        /* Error/Success Messages */
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: 500;
        }

        .error {
            background: #ffebee;
            color: #d32f2f;
        }

        .success {
            background: #e8f5e9;
            color: #388e3c;
        }
    </style>
</head>

<body>
    <div class="bg-img">
        <div class="content">
            <header>Verify if you are registered</header>
            
            <!-- Display Messages -->
            <?php if (isset($_POST['email'])): ?>
                <div class="message <?php echo strpos($output, '✅') !== false ? 'success' : 'error'; ?>">
                    <?php echo $output; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <input type="text" name="company_name" placeholder="Enter Company name" required>
                <input type="email" name="email" placeholder="Enter your company email" required>
                <button class="button" type="submit">Verify</button>
            </form>
        </div>
    </div>
</body>
</html>