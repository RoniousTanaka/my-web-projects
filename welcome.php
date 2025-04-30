<?php
include "connectiondb.php";



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

        select {
            width: 100%;
            height: 50px;
            color: black;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        }

        .button {
            margin-top: 20px;
            background: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
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
            <header>WELCOME TO INTERNREC</header>
            <select id="role">
                <option value="" selected disabled> LOGIN AS </option>
                <option value="Intern">Intern</option>
                <option value="Employer">Employer</option>
                <option value="Admin">Admin</option>
            </select>
            <button class="button" onclick="handleRole()">Proceed</button>
        </div>
    </div>

    <script>
        function handleRole() {
            const selectedValue = document.getElementById('role').value;

            if (selectedValue === "Intern") {
                alert('Welcome Intern! Make sure to provide your registration number and password.');
                window.location.href = 'internlogin.php';
            } else if (selectedValue === "Employer") {
                alert('To access INTERNREC! login');
                window.location.href = 'company/companylogin.php'; // Update the URL
            } else if (selectedValue === "Admin") {
                alert('Login with correct credentials to access admin!');
                window.location.href = 'adminlogin.php';
            } else {
                alert('Please select a role.');
            }
        }
    </script>
</body>

</html>

