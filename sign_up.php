<?php
include "connectiondb.php";  // Include your database connection
include "functions.php";      // Include your functions file (if necessary)

error_reporting(E_ALL);  // Enable error reporting for debugging

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and trim all user inputs
    $fullname = htmlspecialchars(trim($_POST['fullname']), ENT_QUOTES, 'UTF-8');
    $regnumber = trim($_POST['regnumber']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phonenumber = $_POST['mobilenumber'];

    // Check if all fields are filled
    if (!empty($fullname) && !empty($regnumber) && !empty($email) && !empty($phonenumber) && !empty($password)) {

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format!");
        }

        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check for duplicate email, registration number, or fullname
        $checkQuery = "SELECT * FROM users WHERE email = ? OR regnumber = ? OR fullname = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "sss", $email, $regnumber, $fullname);  // Binding email, regnumber, fullname
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);

        // If a duplicate is found, terminate the script
        if (mysqli_num_rows($result) > 0) {
            die("Email, registration number, or fullname already exists!");
        }
        mysqli_stmt_close($checkStmt);

        // Insert the data into the users table (without gender)
        $query = "INSERT INTO users (fullname, regnumber, email, password, phonenumber) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Statement preparation failed: " . mysqli_error($conn));
        }

        // Bind the user inputs to the statement
        mysqli_stmt_bind_param($stmt, "sssss", $fullname, $regnumber, $email, $hashedPassword, $phonenumber);

        // Execute the statement
        if (!mysqli_stmt_execute($stmt)) {
            die("Statement execution failed: " . mysqli_error($conn));
        } else {
            // Success message and redirect
            echo "SignUp was successfull!";
            header("Location: Internlogin.php");  // Redirect to admin page
            exit;  // Always call exit after header redirect
        }
    } else {
        // Handle the case where one or more fields are empty
        die("All fields are required!");
    }
}

mysqli_close($conn);  // Close the database connection
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>|InternReC STUDENT SIGNUP PAGE|</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
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
            /* Adjust opacity for transparency */
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 1000;
            text-align: center;
            padding: 60px 42px;
            width: 410px;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.04);
            box-shadow: -1px 4px 28px 0px rgba(0, 0, 0, 0.75);
            height:max-content;
        }

        .content header {
            color: white;
            font-size: 33px;
            font-weight: 600;
            margin: 0 0 35px 0;
            font-family: 'Montserrat', sans-serif;
        }

        .field {
            position: relative;
            height: 45px;
            width: 100%;
            display: flex;
            background: rgba(255, 255, 255, 0.94);
        }

        .field span {
            color: #222;
            width: 40px;
            line-height: 45px;
        }

        .field input {
            height: 100%;
            width: 100%;
            background: transparent;
            border: none;
            outline: none;
            color: #222;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }

        .space {
            margin-top: 16px;
        }

        .show {
            position: absolute;
            right: 13px;
            font-size: 13px;
            font-weight: 700;
            color: #222;
            display: none;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
        }

        .pass-key:valid~.show {
            display: block;
        }

        .pass {
            text-align: left;
            margin: 10px 0;
        }

        .pass a {
            color: white;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }

        .pass:hover a {
            text-decoration: underline;
        }

        .field input[type="button"] {
            background: #808080;
            /* Solid grey */
            border: none;
            color: white;
            font-size: 18px;
            letter-spacing: 1px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            border-radius: 25px;
            /* Rounded corners */
            box-shadow: 0 4px 15px rgba(128, 128, 128, 0.5), 0 0 25px rgba(128, 128, 128, 0.7);
            /* Glassy effect */
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .field input[type="button"]:hover {
            background: #696969;
            /* Darker grey on hover */
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(128, 128, 128, 0.7), 0 0 30px rgba(128, 128, 128, 0.9);
            /* Stronger glow on hover */
        }

        .login {
            color: white;
            margin: 20px 0;
            font-family: 'Poppins', sans-serif;
        }

        .links {
            display: flex;
            cursor: pointer;
            color: white;
            margin: 0 0 20px 0;
        }

        .facebook,
        .instagram {
            width: 100%;
            height: 45px;
            line-height: 45px;
            margin-left: 10px;
        }

        .facebook {
            margin-left: 0;
            background: #4267B2;
            border: 1px solid #3e61a8;
        }

        .instagram {
            background: #E1306C;
            border: 1px solid #df2060;
        }

        .facebook:hover {
            background: #3e61a8;
        }

        .instagram:hover {
            background: #df2060;
        }

        .links i {
            font-size: 17px;
        }

        i span {
            margin-left: 8px;
            font-weight: 500;
            letter-spacing: 1px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }

        .signup {
            font-size: 15px;
            color: white;
            font-family: 'Poppins', sans-serif;
        }

        .signup a {
            color: #3498db;
            text-decoration: none;
        }

        .signup a:hover {
            text-decoration: underline;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<form action="sign_up.php" method="POST">

    <body>
        <div class="bg-img">
            <div class="content">
                <header>SIGNUP PAGE</header>
                <div>
                    <div class="field">
                        <span class="fa fa-user"></span>
                        <input type="text" required placeholder="Fullname" name="fullname"><br><br>
                    </div><br><br>
                    <div class="field">
                        <span class="fa fa-user"></span>
                        <input type="text" required placeholder="Registration Number" name="regnumber">
                    </div><br><br>
                    <div class="field">
                        <span class="fa fa-user"></span>
                        <input type="email" required placeholder="Email" name="email"><br><br>
                    </div><br><br>
                    <div class="field">
                        <span class="fa fa-user"></span>
                        <input type="phone" required placeholder="+263" name="mobilenumber"><br><br>
                    </div><br><br>
                    <div class="field space">
                        <span class="fa fa-lock"></span>
                        <input type="password" class="pass-key" required placeholder="Password" name="password">
                        <span class="show">SHOW</span>
                    </div>

                    <div class="pass">
                        <br><br>
                    </div>

                    <div class="field">
                        <input type="submit" value="Register">
                    </div>
                </div>
            </div>
        </div>

        <script>
            const pass_field = document.querySelector('.pass-key');
            const showBtn = document.querySelector('.show');
            showBtn.addEventListener('click', function() {
                if (pass_field.type === "password") {
                    pass_field.type = "text";
                    showBtn.textContent = "HIDE";
                    showBtn.style.color = "#3498db";
                } else {
                    pass_field.type = "password";
                    showBtn.textContent = "SHOW";
                    showBtn.style.color = "#222";
                }
            });
        </script>
    </body>
</form>

</html>

