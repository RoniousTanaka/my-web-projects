
<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin portal</title>
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
            background-color: #4F9D9C;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            border-radius: 0 0 15px 15px;
        }

        nav h1 {
            margin: 0;
            font-size: 24px;
        }

        nav a {
            color: white;
            text-decoration: none;
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
            position: vertical;
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
            background-color: #4F9D9C;
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
        .card-container {
            display: flex;
            flex-direction: column;
            gap: 50px; /* Space between cards */
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card {
            width: 200px;
            height: 150px;
            
            position: relative;
        }

        .card:nth-child(1) {
            top: 0;
        }

        .card:nth-child(2) {
            top: 30px;
        }

        .card:nth-child(3) {
            top: 60px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="nav">
        <h1>Admin Desk |internec</h1>
    
            <a href="companyreg.php">register companies</a>
            <a href="Admin.php">student Database</a>
            <a href="#">Matched Interns</a>
            <a href="studentOnWaitinglist.php">Waiting list</a>
            <a href = "verification_ofIntern.php">CV Submited</a>
            <a href="logout.php">Logout</a>
    
    </nav>

    <!-- Dashboard -->
    <div class="dashboard-wrapper">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
<h2> Admin | Dashboard</h2>
        </div>

        <!-- Info Cards -->
        
            <div class="card-container">
                <h2>CVs</h2>
                <?php
// Fetch full names from the database
include "connectiondb.php";

$query = "SELECT full_name FROM  cv_data1"; //
$result = mysqli_query($conn, $query);

    while($row = $result->fetch_assoc()) {
        echo '<div class = "our-team">
        <div class="our-team">
                <b> <a class="name" href="" >    ' . htmlspecialchars($row['full_name']) . '<b></a>
                     </div>';

                }
            ?>
            </div>
            <br></br>
            <div class="card-container">
                <h2> </h2>
                <p><a h ref="">EMPLOYER QUERIES </a></p>

            </div>
            <div class="card-container">
            <h2>Registered companies <?php
// Fetch full names from the database
include "connectiondb.php";

$query = "SELECT company_name, email_address, registration_number FROM companies"; //
$result = mysqli_query($conn, $query);

    while($row = $result->fetch_assoc()) {
        echo '<div class = "our-team">
        <div class="our-team">
                <b> <p class="name" >    ' . htmlspecialchars($row['company_name']) . '<b></p>
                     </div>';

                }


            ?></h2><br></br>
            </div>
        </div>

        <!-- Notifications -->


       



</body>

  <!-- Footer -->
<footer class = "footer">
        <p> 2025 Intern Recruitment System | <a href="#">Privacy Policy</a></p>
    </footer>
   

</html>
