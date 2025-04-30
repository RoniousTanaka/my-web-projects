<?php
include "connectiondb.php";
include "studentname.php";
error_reporting(E_ALL);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Collect and sanitize form data
    $full_name = $conn->real_escape_string($_POST['fname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['mobnum']);
    $position = $conn->real_escape_string($_POST['position']);
    $duration = $conn->real_escape_string($_POST['duration']);
    $department = $conn->real_escape_string($_POST['department']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $address = $conn->real_escape_string($_POST['add']);

    // Insert into database
    $sql = "INSERT INTO applications 
            (full_name, email, phone, position, duration, department, gender, address) 
            VALUES 
            ('$full_name', '$email', '$phone', '$position', '$duration', '$department', '$gender', '$address')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Application submitted successfully!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Application - <?php echo htmlspecialchars($studentName); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            text-align: center;
            color: #333;
        }
        
        .input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 100px;
            resize: vertical;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <h1><?php echo htmlspecialchars($studentName); ?></h1>
        
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Internship Application Form Section -->
        <form method="post" action="">
            <div>
                <label>Full Name</label>
                <input type="text" value="<?php echo htmlspecialchars($studentName); ?>" name="fname" class="input" required>
                
                <label>Email</label>
                <input type="email" placeholder="Your Email" name="email" class="input" required>
                
                <label>Phone Number</label>
                <input type="tel" placeholder="Phone Number" class="input" name="mobnum" required maxlength="10" pattern="[0-9]+">
                
                <label>Internship Position</label>
                <input type="text" placeholder="Internship Position" name="position" class="input" required>
                
                <label>Duration</label>
                <input type="text" value="8 months" name="duration" class="input" required>
                
                <label>Department</label>
                <input type="text" value="Information Security" name="department" class="input" required>
                
                <label>Gender</label>
                <select name="gender" required class="input">
                    <option value="">Choose Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                
                <label>Address</label>
                <textarea placeholder="Enter Address" name="add" required></textarea>
                
                <input type="submit" class="btn-primary" value="Apply Now" name="submit">
            </div>
        </form>
    </div>
</body>
</html>