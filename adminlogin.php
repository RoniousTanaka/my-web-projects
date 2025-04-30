<?php

session_start();
error_reporting(0);
include('connectiondb.php'); // Include database connection

$db_server = "127.0.0.1"; // Database server
$db_username = "root";    // Database username
$db_password = "";         // Database password
$db_name = "interndb";     // Database name

try {
    // Attempt to establish a connection
    $conn = new mysqli($db_server, $db_username, $db_password, $db_name);

    // Check if the connection encountered any errors
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // If successful, you can notify or perform further actions

} catch (Exception $e) {
    // Handle the exception and display the error message
    echo "Error: " . $e->getMessage();
}


if (isset($_POST['login'])) {
    $password = md5($_POST['password']); // Retrieve and hash the password input

    // Check database connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute SQL query to fetch password from tbladmin
    $sql = "SELECT username FROM tbladmin WHERE username = ?"&& "SELECT password FROM tbladmin WHERE password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if password exists and display it
    if ($result && $result->num_rows > 0) {
        // Fetch password and redirect to adminportal
        $data = $result->fetch_assoc();
        $_SESSION['login'] = $_POST['username']; // Set session variable if needed
        header("Location: admin-profile.php"); // Redirect to admin portal
        exit; // Ensure no further code is executed
    } else {
        echo "<script>alert('Invalid Password');</script>";
    }
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login </title>
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
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 999;
            text-align: center;
            padding: 60px 32px;
            width: 370px;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.04);
            box-shadow: -1px 4px 28px 0px rgba(0, 0, 0, 0.75);
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

        .field input[type="submit"] {
            background: #808080;
            border: none;
            color: white;
            font-size: 18px;
            letter-spacing: 1px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(128, 128, 128, 0.5), 0 0 25px rgba(128, 128, 128, 0.7);
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .field input[type="submit"]:hover {
            background: #696969;
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(128, 128, 128, 0.7), 0 0 30px rgba(128, 128, 128, 0.9);
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

<body>
    <form action="admin-profile.php" method="POST">
        <div class="bg-img">
            <div class="content">
                <header><b> Admin</b> | Login</header>
                <div>
                    <div class="field">
                        <span class="fa fa-user"></span>
                        <input type="text" name="username" required placeholder="username">
                    </div>
                    <div class="field space">
                        <span class="fa fa-lock"></span>
                        <input type="password" class="pass-key" name="password" required placeholder="Password">
                        <span class="show">SHOW</span>
                    </div>
                    <div class="pass">
                        <a href="#">Forgot Password?</a>
                    </div>
                    <div class="field">
                        <input type="submit" value="LOGIN">
                    </div>
                </div>

            </div>
        </div>
    </form>
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

</html>
