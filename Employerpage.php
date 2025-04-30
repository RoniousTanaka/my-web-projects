<?php

?>
 <!-- my html code -->


 <?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer </title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        /* Navbar Style */
        nav {
            background-color:#aae5e9;
            padding: 15px 30px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            font-size: 24px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 18px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Dashboard Wrapper */
        .dashboard-wrapper {
            display: flex;
            flex-direction: column;
            margin: 20px;
        }

        /* Dashboard Header */
        .dashboard-header {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .dashboard-header h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            font-size: 18px;
            color: #777;
        }

        /* Info Cards */
        .info-cards {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            height: fit-content;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            padding: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .card h3 {
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 18px;
            color: #333;
        }

        .card .status {
            font-weight: bold;
            color: #4CAF50;
        }

        /* Notification Section */
        .notifications {
            margin-top: 40px;
        }

        .notification-item {
            background-color: #ffffff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .notification-item h4 {
            font-size: 20px;
            color: #333;
        }

        .notification-item p {
            color: #555;
            font-size: 16px;
        }

        .notification-item span {
            font-size: 14px;
            color: #888;
        }

        /* Footer */
        footer {
            background-color:#6bf3fd;
            padding: 15px;
            color: white;
            text-align: center;
            margin-top: 40px;
        }

        footer a {
            color: white;
            text-decoration: none;
            background-image: 'C:\xampp\htdocs\Internship Rucruitment System\admin\images\1e6ae4ada992769567b71815f124fac51575274951.jpg';
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <h1>Employer Desk | Intern Recruitment</h1>
        <div>
            <a href="#"> Internship Criteria </a>
            <a href="">view Student CVs </a>
            <a href="#">Interns Application forms</a>
            <a href="#"></a>
            <a href="#">Logout</a>
        </div>
    </nav>

    <!-- Dashboard -->
    <div class="dashboard-wrapper">
        <!-- Dashboard Header -->
        <div class="dashboard-header">

        </div>

        <!-- Info Cards -->
        <div class="info-cards">
            <div class="card">
                <h2>STUDENT QUERIES</h2>
                <p> <span class="status"><a href = "Admin.php"></a></span></p>
            </div>
            <div class="card">
                <h2>EMPLOYER QUERIES </h2>
                <p><a h ref=""> </a></p>
            </div>
            <div class="card">
            <h2>REGISTER A NEW COMPANY </h2>
            </div>
        </div>

        <!-- Notifications -->
        <div class="info-cards">
            <div class="card">
       <h2>Reccomendations from Employees</h2>

            </div>
            <div class="card">
                <h2>Employer Chatting room</h2>
                <p></p>

            </div>
            <div class="card">
                <h2>Assess available Opportunities</h2>
                <p></p>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class = "footer">
        <p>&copy; 2025 Intern Recruitment System | <a href="#">Privacy Policy</a></p>
    </footer>

</body>
</html>
