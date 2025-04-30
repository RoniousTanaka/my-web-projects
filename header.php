<?php
include 'studentname.php';
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session

// Database connection settings
$host = "localhost";
$dbname = "interndb"; // Replace with your database name
$username = "root"; // Replace with your username
$password = ""; // Replace with your password

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check session variable for regnumber to identify the student
if (isset($_SESSION['regnumber'])) {
    $regnumber = $_SESSION['regnumber'];

    // Fetch the student's name and other details from the database
    $sql = "SELECT fullname FROM users WHERE regnumber = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Failed to prepare SQL statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "s", $regnumber);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $studentName);

    // Check if a result is found
    if (mysqli_stmt_fetch($stmt)) {
        $studentName = htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); // Sanitize the name
    } else {
        $studentName = "Guest"; // Default value if no user is found
    }
    mysqli_stmt_close($stmt);
} else {
    die("Session variable regnumber is not set.");
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f1f8ff;
            margin: 0;
            padding: 0;
            color: #333;
        }

       nav {
    background-color: #4F9D9C;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    border-radius: 0 0 15px 15px;}

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

        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 250px;
            height: flex-direction;
            background-color: #4F9D9C;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            border-radius: 0 15px 15px 0;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-section img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile-section h2 {
            color: #fff;
            font-size: 18px;
        }

        .button {
            background-color: #ffdd9b;
            border: none;
            border-radius: 25px;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #ffca6b;
        }

        main {
            margin-left: 270px;
            padding: 20px;
        }

        #contentArea {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            min-height: 80vh;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: space-around;
            }

            main {
                margin-left: 0;
                padding: 10px;
            }

            #contentArea {
                min-height: 500px;
            }
        }
    </style>
</head>
<body>

    <nav>
        <h1>Student Portal</h1>
        <a href="chatBot.php">Help from chatBot</a>
        <a href="logout.php">Logout</a>
        
    </nav>

    <aside class="sidebar">
        <div class="profile-section">
        
            <h3>Welcome, <?php echo $studentName; ?></h3>
        </div>
        <button class="button" onclick="loadPage('studentdashboard.php')">Dashboard</button>
        <button class="button" onclick="loadPage('profiles.php')">Create Profile</button>
        <button class="button" onclick="loadPage('cvmaker.php')">Create CV</button>
        <button class="button" onclick="loadPage('internApplicationform.php')">Application Form</button>
    </aside>

    <main>
        <div id="contentArea">Loading...</div>
    </main>

    <script>
        // Function to load page content dynamically into the content area
        function loadPage(url) {
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(data => {
                    document.getElementById('contentArea').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('contentArea').innerHTML = `<p style="color:red;">Error loading page: ${error.message}</p>`;
                });
        }

        // Load default page on load
        window.onload = function () {
            loadPage('studentdashboard.php');
        };
    </script>

</body>
</html>
