<?php

include('connectiondb.php'); // Make sure this path is correct

// Optional: Restrict to logged-in admins/companies
// if (!isset($_SESSION['company_email'])) {
//     header("Location: login.php");
//     exit();
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review All Applicants</title>
    <style>
        .flag {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }
        .red { background-color: #e74c3c; }
        .yellow { background-color: #f1c40f; color: black; }
        .green { background-color: #2ecc71; }
    </style>
</head>
<body>
    <h1>Student Application Review</h1>

  
        <?php
// Fetch full names from the database
include "connectiondb.php";

$query = "SELECT * FROM  cv_data1"; //
$result = mysqli_query($conn, $query);

    while($row = $result->fetch_assoc()) {
        echo '<div class = "our-team">
        <div class="our-team">
                <b> <p class="name" >    ' . htmlspecialchars($row['full_name']) . '<b></p>
                 <b> <p class="name" >    ' . htmlspecialchars($row['email']) . '<b></p>
                     </div>';

                }


            ?>
            <p><strong>Resume:</strong> <a href="<?= htmlspecialchars($row['*']) ?>" target="_blank">View CV</a></p>
            <p>
                <strong>Status:</strong>
                <span class="flag <?= $row['status_flag'] ?>">
                    <?= strtoupper($row['status_flag']) ?> FLAG
                </span>
            </p>

            <!-- Form to update flag -->
            <form method="post" action="update_flag.php">
                <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                <label for="flag">Update Flag:</label>
                <select name="flag" required>
                    <option value="">-- Select --</option>
                    <option value="green">Green</option>
                    <option value="yellow">Yellow</option>
                    <option value="red">Red</option>
                </select>
                <button type="submit">Save</button>
            </form>
        </div>
    </body>
</html>
