<?php
// Turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = 'localhost';
$dbname = 'interndb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Debug: Check if the request is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Debug: Check the data being received
        

        // Sanitize and validate inputs
        $companyName = htmlspecialchars(trim($_POST['companyName'] ?? ''));
        $companyAddress = htmlspecialchars(trim($_POST['companyAddress'] ?? ''));
        $registrationNumber = htmlspecialchars(trim($_POST['registrationNumber'] ?? ''));

        if (empty($companyName) || empty($companyAddress) || empty($registrationNumber)) {
            echo "All fields are required.";
            exit;
        }

        // Insert into the database
        $sql = "INSERT INTO companies (company_name, email_address, registration_number)
                VALUES (:company_name, :email_address, :registration_number)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':email_address', $companyAddress);
        $stmt->bindParam(':registration_number', $registrationNumber);

        if ($stmt->execute()) {
            echo "Check below for the registered company";
        } else {
            echo "Failed to register company.";
        }
    } else {
        
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register Company</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 30px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background-color: white;
      padding: 20px;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    input, label, button {
      display: block;
      width: 100%;
      margin: 10px 0;
    }
    input, button {
      padding: 10px;
    }
    button {
      background: #4CAF50;
      color: white;
      border: none;
    }

    .our-team {
            padding: 20px;
            margin: 10px;
            background:#c3e7e5; /* Slightly transparent background */
            text-align: center;
            border-radius: 10px; /* Rounded corners */
            flex: 1 1 calc(25% - 20px); /* 4 items per row */
            box-sizing: border-box;
            border-radius: 55px;
            border-color: blue;}

  </style>
</head>
<body>
  <div class="container">
    <h2>Company Registration</h2>
    <form method="POST" action="companyreg.php">
      <label for="companyName">Company Name:</label>
      <input type="text" id="companyName" name="companyName" required />

      <label for="companyAddress">Email Address:</label>
      <input type="email" id="companyAddress" name="companyAddress" required />

      <label for="registrationNumber">Registration Number:</label>
      <input type="text" id="registrationNumber" name="registrationNumber" required />

      <button type="submit">Register</button>
    </form>

  </div>
  <div class = "container">
<h2> Registered companies</h2>
<p> <?php 
// Fetch full names from the database
include "connectiondb.php";

$query = "SELECT company_name, email_address, registration_number FROM companies"; //
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {

  
    while($column = $result->fetch_assoc()) {
        echo '<div class = "containter">
        <div class="our-team">
                <h3 class="name" >Company Name   <b>  ' . htmlspecialchars($column['company_name']) . '<b></h3><br></br><br></br>
                  <h3 class="name"> Rgistration Number  ' . htmlspecialchars($column['registration_number']) . '</h3><br></br><br></br>
                    <h3 class="name"> Address  ' . htmlspecialchars($column['email_address']) . '</h3><br></br><br></br>
                    </div>';

                }

            }
            ?>
</div>
</body>
</html>
